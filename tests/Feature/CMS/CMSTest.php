<?php

declare(strict_types=1);

namespace Tests\Feature\CMS;

use App\Models\User;
use App\Modules\CMS\Models\Category;
use App\Modules\CMS\Models\Post;
use App\Modules\CMS\Models\Page;
use App\Modules\CMS\Models\Menu;
use App\Modules\CMS\Models\MenuItem;
use App\Modules\System\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CMSTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $editor;
    protected User $guestUser;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Seed System permissions & roles
        $this->seed(\App\Modules\System\database\seeders\SystemSeeder::class);
        // 2. Seed CMS default records
        $this->seed(\App\Modules\CMS\database\seeders\CMSSeeder::class);

        // 3. Retrieve users
        $this->superAdmin = User::where('email', 'hasbialaziz67@gmail.com')->first();
        
        $this->editor = User::factory()->create();
        $this->editor->assignRole('Editor');

        $this->guestUser = User::factory()->create();
    }

    /**
     * Test CMS authorization rules.
     */
    public function test_only_authorized_users_can_access_cms_admin(): void
    {
        // Guests cannot access posts list
        $response = $this->actingAs($this->guestUser)->get(route('admin.posts.index'));
        $response->assertStatus(403);

        // Editor can access posts list
        $response = $this->actingAs($this->editor)->get(route('admin.posts.index'));
        $response->assertStatus(200);

        // Super Admin can access posts list
        $response = $this->actingAs($this->superAdmin)->get(route('admin.posts.index'));
        $response->assertStatus(200);
    }

    /**
     * Test Posts CRUD lifecycle, unique slug generation, and audit logging.
     */
    public function test_post_crud_lifecycle_and_audit_logging(): void
    {
        $this->actingAs($this->superAdmin);

        $category = Category::first();

        // 1. Create Post
        $postData = [
            'title' => 'Pengumuman Libur Semester',
            'slug' => 'pengumuman-libur-semester',
            'excerpt' => 'Informasi libur sekolah.',
            'content' => '<p>Libur semester ganjil dimulai tanggal 20 Desember 2026.</p>',
            'status' => 'published',
            'categories' => [$category->id],
            'seo_title' => 'Libur Semester 2026',
            'seo_description' => 'Tanggal libur semester ganjil Sekolah Hub.'
        ];

        $response = $this->post(route('admin.posts.store'), $postData);
        $response->assertRedirect(route('admin.posts.index'));

        // Verify database records
        $this->assertDatabaseHas('posts', [
            'title' => 'Pengumuman Libur Semester',
            'slug' => 'pengumuman-libur-semester',
            'status' => 'published',
            'author_id' => $this->superAdmin->id,
        ]);

        $post = Post::where('slug', 'pengumuman-libur-semester')->first();
        $this->assertNotNull($post);
        $this->assertTrue($post->categories->contains($category->id));

        // Check Audit Log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'cms.post.create',
            'user_id' => $this->superAdmin->id,
            'auditable_type' => Post::class,
            'auditable_id' => $post->id,
        ]);

        // 2. Create Duplicate Title Post (Slug auto-uniquification)
        $duplicateResponse = $this->post(route('admin.posts.store'), [
            'title' => 'Pengumuman Libur Semester',
            'content' => 'Sama dengan libur di atas.',
            'status' => 'draft'
        ]);
        $duplicateResponse->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'title' => 'Pengumuman Libur Semester',
            'slug' => 'pengumuman-libur-semester-1',
            'status' => 'draft',
        ]);

        // 3. Update Post
        $updateData = [
            'title' => 'Pengumuman Libur Semester Ganjil',
            'slug' => 'pengumuman-libur-semester',
            'excerpt' => 'Revisi info libur.',
            'content' => '<p>Revisi: Libur semester dimulai tanggal 18 Desember 2026.</p>',
            'status' => 'published',
            'categories' => [$category->id],
        ];

        $updateResponse = $this->put(route('admin.posts.update', $post->id), $updateData);
        $updateResponse->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Pengumuman Libur Semester Ganjil',
            'excerpt' => 'Revisi info libur.',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'cms.post.update',
            'user_id' => $this->superAdmin->id,
            'auditable_id' => $post->id,
        ]);

        // 4. Delete Post
        $deleteResponse = $this->delete(route('admin.posts.destroy', $post->id));
        $deleteResponse->assertRedirect(route('admin.posts.index'));

        // Soft deletes
        $this->assertSoftDeleted('posts', ['id' => $post->id]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'cms.post.delete',
            'user_id' => $this->superAdmin->id,
            'auditable_id' => $post->id,
        ]);
    }

    /**
     * Test Pages CRUD lifecycle and hierarchy relationship.
     */
    public function test_page_crud_lifecycle_and_hierarchy(): void
    {
        $this->actingAs($this->superAdmin);

        // 1. Create Page
        $pageData = [
            'title' => 'Fasilitas Sekolah',
            'content' => '<p>Daftar laboratorium, lapangan olahraga, dan gedung.</p>',
            'status' => 'published',
        ];

        $response = $this->post(route('admin.pages.store'), $pageData);
        $response->assertRedirect(route('admin.pages.index'));

        $this->assertDatabaseHas('pages', [
            'title' => 'Fasilitas Sekolah',
            'slug' => 'fasilitas-sekolah',
            'parent_id' => null,
        ]);

        $parentPage = Page::where('slug', 'fasilitas-sekolah')->first();

        // 2. Create Child Page
        $childData = [
            'parent_id' => $parentPage->id,
            'title' => 'Laboratorium Komputer',
            'content' => '<p>Lab Komputer dengan 40 PC terbaru.</p>',
            'status' => 'published',
        ];

        $response = $this->post(route('admin.pages.store'), $childData);
        $response->assertRedirect(route('admin.pages.index'));

        $this->assertDatabaseHas('pages', [
            'title' => 'Laboratorium Komputer',
            'slug' => 'laboratorium-komputer',
            'parent_id' => $parentPage->id,
        ]);

        $childPage = Page::where('slug', 'laboratorium-komputer')->first();

        // 3. Update Page & check loop protection
        $updateResponse = $this->put(route('admin.pages.update', $parentPage->id), [
            'parent_id' => $parentPage->id, // Attempt self-parenting
            'title' => 'Fasilitas Sekolah Updated',
            'content' => 'Isi',
            'status' => 'published',
        ]);
        $updateResponse->assertSessionHasErrors('parent_id');

        // 4. Delete parent page detach children
        $deleteResponse = $this->delete(route('admin.pages.destroy', $parentPage->id));
        $deleteResponse->assertRedirect(route('admin.pages.index'));

        $this->assertSoftDeleted('pages', ['id' => $parentPage->id]);

        $childPage->refresh();
        $this->assertNull($childPage->parent_id);
    }

    /**
     * Test Categories CRUD lifecycle.
     */
    public function test_category_crud_lifecycle(): void
    {
        $this->actingAs($this->superAdmin);

        // 1. Create Category
        $response = $this->post(route('admin.categories.store'), [
            'name' => 'E-Learning',
            'description' => 'Materi belajar online.',
        ]);
        $response->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'name' => 'E-Learning',
            'slug' => 'e-learning',
        ]);

        $category = Category::where('slug', 'e-learning')->first();

        // 2. Update Category
        $response = $this->put(route('admin.categories.update', $category->id), [
            'name' => 'Pembelajaran Digital',
            'description' => 'Materi e-learning.',
        ]);
        $response->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Pembelajaran Digital',
        ]);

        // 3. Delete Category
        $response = $this->delete(route('admin.categories.destroy', $category->id));
        $response->assertRedirect(route('admin.categories.index'));

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    /**
     * Test Menu Item structure saving and URL resolver.
     */
    public function test_menu_structure_management_and_resolver(): void
    {
        $this->actingAs($this->superAdmin);

        $menu = Menu::first();
        $page = Page::first();
        $post = Post::first();
        $category = Category::first();

        // Save new menu structure with nested items
        $itemsStructure = [
            [
                'title' => 'Home',
                'type' => 'custom',
                'url' => '/',
                'sort_order' => 0,
            ],
            [
                'title' => 'Profil',
                'type' => 'page',
                'reference_type' => Page::class,
                'reference_id' => $page->id,
                'sort_order' => 1,
                'children' => [
                    [
                        'title' => 'Berita Utama',
                        'type' => 'post',
                        'reference_type' => Post::class,
                        'reference_id' => $post->id,
                        'sort_order' => 0,
                    ]
                ]
            ],
            [
                'title' => 'Kategori',
                'type' => 'category',
                'reference_type' => Category::class,
                'reference_id' => $category->id,
                'sort_order' => 2,
            ]
        ];

        $response = $this->post(route('admin.menus.save_structure', $menu->id), [
            'items_json' => json_encode($itemsStructure),
        ]);
        $response->assertRedirect(route('admin.menus.builder', $menu->id));

        // Assert items saved in DB
        $this->assertDatabaseHas('menu_items', [
            'menu_id' => $menu->id,
            'title' => 'Home',
            'type' => 'custom',
            'url' => '/',
            'parent_id' => null,
        ]);

        $parentItem = MenuItem::where('menu_id', $menu->id)->where('title', 'Profil')->first();
        $this->assertNotNull($parentItem);

        $this->assertDatabaseHas('menu_items', [
            'menu_id' => $menu->id,
            'title' => 'Berita Utama',
            'type' => 'post',
            'parent_id' => $parentItem->id,
        ]);

        // Test URL dynamic resolution
        $homeItem = MenuItem::where('title', 'Home')->first();
        $this->assertEquals('/', $homeItem->url);

        $pageItem = MenuItem::where('title', 'Profil')->first();
        $this->assertEquals(route('public.pages.show', $page->slug), $pageItem->url);

        $postItem = MenuItem::where('title', 'Berita Utama')->first();
        $this->assertEquals(route('public.posts.show', $post->slug), $postItem->url);

        $catItem = MenuItem::where('title', 'Kategori')->first();
        $this->assertEquals(route('public.categories.show', $category->slug), $catItem->url);
    }

    /**
     * Test public routes.
     */
    public function test_public_visitor_routes(): void
    {
        $post = Post::where('status', 'published')->first();
        $page = Page::where('status', 'published')->first();
        $category = Category::first();

        // 1. Published Post Detail
        $response = $this->get(route('public.posts.show', $post->slug));
        $response->assertStatus(200);
        $response->assertSee($post->title);

        // 2. Published Page Detail
        $response = $this->get(route('public.pages.show', $page->slug));
        $response->assertStatus(200);
        $response->assertSee($page->title);

        // 3. Category Archive
        $response = $this->get(route('public.categories.show', $category->slug));
        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    /**
     * Test HTML sanitization.
     */
    public function test_html_content_is_sanitized(): void
    {
        $this->actingAs($this->superAdmin);

        $category = Category::first();

        // 1. Create Post with XSS payload and Trix custom element
        $postData = [
            'title' => 'Post Sanitasi Tes',
            'slug' => 'post-sanitasi-tes',
            'excerpt' => 'Tes XSS.',
            'content' => '<script>alert("XSS")</script><p>Konten aman <strong>tebal</strong>.</p><figure data-trix-attachment="test-data" class="attachment"><figcaption class="caption">Foto</figcaption></figure>',
            'status' => 'published',
            'categories' => [$category->id],
        ];

        $response = $this->post(route('admin.posts.store'), $postData);
        $response->assertRedirect(route('admin.posts.index'));

        // Retrieve saved post
        $post = Post::where('slug', 'post-sanitasi-tes')->first();
        $this->assertNotNull($post);

        // Assert XSS script is stripped
        $this->assertStringNotContainsString('<script>', $post->content->toTrixHtml());
        $this->assertStringNotContainsString('alert("XSS")', $post->content->toTrixHtml());

        // Assert safe HTML elements and custom Trix elements are preserved
        $this->assertStringContainsString('Konten aman <strong>tebal</strong>.', $post->content->toTrixHtml());
        $this->assertStringContainsString('<figure data-trix-attachment="test-data" class="attachment">', $post->content->toTrixHtml());
        $this->assertStringContainsString('<figcaption class="caption">Foto</figcaption>', $post->content->toTrixHtml());
    }
}
