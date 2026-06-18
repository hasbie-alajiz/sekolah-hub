<?php

declare(strict_types=1);

namespace App\Modules\CMS\Contracts;

use Illuminate\Support\Collection;

interface CMSServiceInterface
{
    /**
     * Get published posts list.
     *
     * @param int $limit
     * @return Collection
     */
    public function getPublishedPosts(int $limit = 5): Collection;

    /**
     * Get a page by its slug.
     *
     * @param string $slug
     * @return object|null
     */
    public function getPageBySlug(string $slug): ?object;

    /**
     * Get a menu by its location.
     *
     * @param string $location
     * @return object|null
     */
    public function getMenuByLocation(string $location): ?object;
}
