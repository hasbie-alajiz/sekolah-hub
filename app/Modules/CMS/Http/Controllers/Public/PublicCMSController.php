<?php

declare(strict_types=1);

namespace App\Modules\CMS\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Modules\CMS\Models\Post;
use App\Modules\CMS\Models\Page;
use App\Modules\CMS\Models\Category;
use Illuminate\View\View;

class PublicCMSController extends Controller
{
    public function showPost(string $slug): View
    {
        $post = Post::with(['author', 'categories'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('cms::public.posts.show', compact('post'));
    }

    public function showPage(string $slug): View
    {
        $page = Page::with('parent')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('cms::public.pages.show', compact('page'));
    }

    public function showCategory(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $posts = $category->posts()
            ->with(['author', 'categories'])
            ->where('status', 'published')
            ->latest()
            ->paginate(10);

        return view('cms::public.categories.show', compact('category', 'posts'));
    }
}
