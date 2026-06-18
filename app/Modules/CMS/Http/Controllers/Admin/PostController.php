<?php

declare(strict_types=1);

namespace App\Modules\CMS\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\CMS\Models\Post;
use App\Modules\CMS\Models\Category;
use App\Modules\CMS\Http\Requests\StorePostRequest;
use App\Modules\CMS\Http\Requests\UpdatePostRequest;
use App\Modules\CMS\Actions\CreatePostAction;
use App\Modules\CMS\Actions\UpdatePostAction;
use App\Modules\CMS\Actions\DeletePostAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private CreatePostAction $createPostAction,
        private UpdatePostAction $updatePostAction,
        private DeletePostAction $deletePostAction
    ) {
    }

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Post::class);

        $query = Post::with(['author', 'categories']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->input('category_id'));
            });
        }

        $posts = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('cms::admin.posts.index', compact('posts', 'categories'));
    }

    public function create(): View
    {
        Gate::authorize('create', Post::class);

        $categories = Category::all();
        $mediaList = \App\Modules\Media\Models\Media::latest()->get();

        return view('cms::admin.posts.create', compact('categories', 'mediaList'));
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $this->createPostAction->execute($request->validated());

        return redirect()->route('admin.posts.index')
            ->with('success', 'Berita berhasil disimpan.');
    }

    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        $categories = Category::all();
        $mediaList = \App\Modules\Media\Models\Media::latest()->get();

        return view('cms::admin.posts.edit', compact('post', 'categories', 'mediaList'));
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $this->updatePostAction->execute($post, $request->validated());

        return redirect()->route('admin.posts.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $this->deletePostAction->execute($post);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Berita berhasil dihapus.');
    }
}
