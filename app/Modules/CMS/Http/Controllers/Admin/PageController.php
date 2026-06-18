<?php

declare(strict_types=1);

namespace App\Modules\CMS\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\CMS\Models\Page;
use App\Modules\CMS\Http\Requests\StorePageRequest;
use App\Modules\CMS\Http\Requests\UpdatePageRequest;
use App\Modules\CMS\Actions\CreatePageAction;
use App\Modules\CMS\Actions\UpdatePageAction;
use App\Modules\CMS\Actions\DeletePageAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(
        private CreatePageAction $createPageAction,
        private UpdatePageAction $updatePageAction,
        private DeletePageAction $deletePageAction
    ) {
    }

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Page::class);

        $query = Page::with('parent');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $pages = $query->latest()->paginate(10)->withQueryString();

        return view('cms::admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        Gate::authorize('create', Page::class);

        $parentPages = Page::all();
        $mediaList = \App\Modules\Media\Models\Media::latest()->get();

        return view('cms::admin.pages.create', compact('parentPages', 'mediaList'));
    }

    public function store(StorePageRequest $request): RedirectResponse
    {
        $this->createPageAction->execute($request->validated());

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman berhasil disimpan.');
    }

    public function edit(Page $page): View
    {
        Gate::authorize('update', $page);

        $parentPages = Page::where('id', '!=', $page->id)->get();
        $mediaList = \App\Modules\Media\Models\Media::latest()->get();

        return view('cms::admin.pages.edit', compact('page', 'parentPages', 'mediaList'));
    }

    public function update(UpdatePageRequest $request, Page $page): RedirectResponse
    {
        Gate::authorize('update', $page);

        $this->updatePageAction->execute($page, $request->validated());

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        Gate::authorize('delete', $page);

        $this->deletePageAction->execute($page);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman berhasil dihapus.');
    }
}
