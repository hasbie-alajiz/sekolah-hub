<?php

declare(strict_types=1);

namespace App\Modules\CMS\Services;

use App\Modules\CMS\Contracts\CMSServiceInterface;
use App\Modules\CMS\Models\Post;
use App\Modules\CMS\Models\Page;
use App\Modules\CMS\Models\Menu;
use Illuminate\Support\Collection;

class CMSService implements CMSServiceInterface
{
    public function getPublishedPosts(int $limit = 5): Collection
    {
        return Post::with('categories')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getPageBySlug(string $slug): ?object
    {
        return Page::where('slug', $slug)
            ->where('status', 'published')
            ->first();
    }

    public function getMenuByLocation(string $location): ?object
    {
        return Menu::with(['items' => function ($query) {
                $query->whereNull('parent_id')->orderBy('sort_order');
            }, 'items.children'])
            ->where('location', $location)
            ->first();
    }
}
