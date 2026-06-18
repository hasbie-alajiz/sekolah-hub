<?php

declare(strict_types=1);

namespace App\Modules\CMS\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\CMS\Models\Menu;
use App\Modules\CMS\Models\Page;
use App\Modules\CMS\Models\Post;
use App\Modules\CMS\Models\Category;
use App\Modules\CMS\Http\Requests\StoreMenuRequest;
use App\Modules\CMS\Actions\SaveMenuStructureAction;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {
    }

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Menu::class);

        $query = Menu::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        }

        $menus = $query->latest()->paginate(10)->withQueryString();

        return view('cms::admin.menus.index', compact('menus'));
    }

    public function create(): View
    {
        Gate::authorize('create', Menu::class);

        return view('cms::admin.menus.create');
    }

    public function store(StoreMenuRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);

        // Handle unique slug
        $originalSlug = $slug;
        $count = 1;
        while (Menu::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        // If location is provided, clear other menus with the same location (since location is single-menu usually)
        if (!empty($data['location'])) {
            Menu::where('location', $data['location'])->update(['location' => null]);
        }

        $menu = Menu::create([
            'name' => $data['name'],
            'slug' => $slug,
            'location' => $data['location'] ?? null,
        ]);

        $this->systemService->logAudit('cms.menu.create', $menu, null, [
            'name' => $menu->name,
            'slug' => $menu->slug,
            'location' => $menu->location,
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil disimpan.');
    }

    public function edit(Menu $menu): View
    {
        Gate::authorize('update', $menu);

        return view('cms::admin.menus.edit', compact('menu'));
    }

    public function update(StoreMenuRequest $request, Menu $menu): RedirectResponse
    {
        Gate::authorize('update', $menu);

        $data = $request->validated();
        $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);

        if ($slug !== $menu->slug) {
            $originalSlug = $slug;
            $count = 1;
            while (Menu::where('slug', $slug)->where('id', '!=', $menu->id)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }
        }

        $oldValues = [
            'name' => $menu->name,
            'slug' => $menu->slug,
            'location' => $menu->location,
        ];

        // If location is changed/provided, clear other menus with the same location
        if (!empty($data['location']) && $data['location'] !== $menu->location) {
            Menu::where('location', $data['location'])->where('id', '!=', $menu->id)->update(['location' => null]);
        }

        $menu->update([
            'name' => $data['name'],
            'slug' => $slug,
            'location' => $data['location'] ?? null,
        ]);

        $this->systemService->logAudit('cms.menu.update', $menu, $oldValues, [
            'name' => $menu->name,
            'slug' => $menu->slug,
            'location' => $menu->location,
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        Gate::authorize('delete', $menu);

        $oldValues = [
            'name' => $menu->name,
            'slug' => $menu->slug,
        ];

        $menu->items()->delete();
        $menu->delete();

        $this->systemService->logAudit('cms.menu.delete', $menu, $oldValues, null);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

    public function builder(Menu $menu): View
    {
        Gate::authorize('update', $menu);

        $menuItems = $menu->items()
            ->whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        $pages = Page::where('status', 'published')->get();
        $posts = Post::where('status', 'published')->get();
        $categories = Category::all();

        return view('cms::admin.menus.builder', compact('menu', 'menuItems', 'pages', 'posts', 'categories'));
    }

    public function saveStructure(Request $request, Menu $menu, SaveMenuStructureAction $action): RedirectResponse
    {
        Gate::authorize('update', $menu);

        if ($request->has('items_json')) {
            $request->merge([
                'items' => json_decode($request->input('items_json', '[]'), true)
            ]);
        }

        $request->validate([
            'items' => ['nullable', 'array'],
        ]);

        $action->execute($menu, $request->input('items', []));

        return redirect()->route('admin.menus.builder', $menu->id)
            ->with('success', 'Struktur menu berhasil disimpan.');
    }
}
