<?php

declare(strict_types=1);

namespace Tests\Feature\Media;

use App\Models\User;
use App\Modules\Media\Models\Media;
use App\Modules\Media\Models\MediaFolder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaUploadTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Fake the public storage disk
        Storage::fake('public');

        // Create an admin user for authentication
        $this->user = User::factory()->create();
    }

    /**
     * Test uploading a valid image file.
     */
    public function test_user_can_upload_valid_image(): void
    {
        $this->actingAs($this->user);

        $file = UploadedFile::fake()->image('school_logo.jpg', 1200, 800);

        $response = $this->post(route('admin.media.store'), [
            'file' => $file,
            'caption' => 'Sekolah Logo',
            'alt_text' => 'Logo Sekolah Hub',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        // Assert record exists in media table
        $this->assertDatabaseHas('media', [
            'original_name' => 'school_logo.jpg',
            'mime_type' => 'image/jpeg',
            'caption' => 'Sekolah Logo',
            'alt_text' => 'Logo Sekolah Hub',
        ]);

        $media = Media::first();
        $this->assertNotNull($media);

        // Assert physical file exists
        Storage::disk('public')->assertExists($media->path);

        // Assert variants are generated
        $this->assertDatabaseHas('media_variants', [
            'media_id' => $media->id,
            'variant' => 'thumbnail',
            'width' => 150,
            'height' => 150,
        ]);

        $this->assertDatabaseHas('media_variants', [
            'media_id' => $media->id,
            'variant' => 'medium',
            'width' => 600,
        ]);

        // Assert physical variant files exist
        foreach ($media->variants as $variant) {
            Storage::disk('public')->assertExists($variant->path);
        }
    }

    /**
     * Test uploading a valid document file.
     */
    public function test_user_can_upload_document_without_generating_variants(): void
    {
        $this->actingAs($this->user);

        $file = UploadedFile::fake()->create('panduan_ppdb.pdf', 500, 'application/pdf');

        $response = $this->post(route('admin.media.store'), [
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        // Assert media is stored
        $this->assertDatabaseHas('media', [
            'original_name' => 'panduan_ppdb.pdf',
            'mime_type' => 'application/pdf',
        ]);

        $media = Media::first();
        Storage::disk('public')->assertExists($media->path);

        // Assert NO variants are created
        $this->assertEquals(0, $media->variants()->count());
    }

    /**
     * Test uploading an invalid file type throws validation errors.
     */
    public function test_user_cannot_upload_invalid_file_type(): void
    {
        $this->actingAs($this->user);

        // php files are not whitelisted
        $file = UploadedFile::fake()->create('script.php', 10, 'text/x-php');

        $response = $this->post(route('admin.media.store'), [
            'file' => $file,
        ]);

        $response->assertSessionHasErrors(['file']);
        $this->assertDatabaseEmpty('media');
    }

    /**
     * Test creating a virtual folder and uploading inside it.
     */
    public function test_user_can_create_folder_and_upload_inside(): void
    {
        $this->actingAs($this->user);

        // 1. Create a folder
        $response = $this->post(route('admin.media.folder.create'), [
            'name' => 'News Images',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('media_folders', [
            'name' => 'News Images',
            'slug' => 'news-images',
        ]);

        $folder = MediaFolder::first();

        // 2. Upload image inside this folder
        $file = UploadedFile::fake()->image('news_thumbnail.png', 300, 200);
        $response = $this->post(route('admin.media.store'), [
            'file' => $file,
            'folder_id' => $folder->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('media', [
            'original_name' => 'news_thumbnail.png',
            'folder_id' => $folder->id,
        ]);
    }

    /**
     * Test soft deleting a media file.
     */
    public function test_user_can_soft_delete_media(): void
    {
        $this->actingAs($this->user);

        // Upload a file first
        $file = UploadedFile::fake()->image('image.jpg');
        $media = app(\App\Modules\Media\Contracts\MediaServiceInterface::class)->upload($file);

        $this->assertDatabaseHas('media', ['id' => $media->id, 'deleted_at' => null]);

        // Delete the file
        $response = $this->delete(route('admin.media.destroy', $media->id));

        $response->assertRedirect();
        
        // Assert record is soft deleted in DB
        $this->assertSoftDeleted('media', ['id' => $media->id]);
        
        // Assert physical file still exists on disk (soft delete keeps files)
        Storage::disk('public')->assertExists($media->path);
    }
}
