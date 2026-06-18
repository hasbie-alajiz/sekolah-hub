<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Modules\CMS\Models\Page;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreatePageAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {
    }

    public function execute(array $data): Page
    {
        return DB::transaction(function () use ($data) {
            $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
            
            // Handle unique slug
            $originalSlug = $slug;
            $count = 1;
            while (Page::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }

            $page = Page::create([
                'parent_id' => !empty($data['parent_id']) ? (int) $data['parent_id'] : null,
                'title' => $data['title'],
                'slug' => $slug,
                'content' => $data['content'],
                'featured_media_id' => !empty($data['featured_media_id']) ? (int) $data['featured_media_id'] : null,
                'status' => $data['status'] ?? 'draft',
                'seo_title' => $data['seo_title'] ?? null,
                'seo_description' => $data['seo_description'] ?? null,
            ]);

            // Log Audit
            $this->systemService->logAudit('cms.page.create', $page, null, [
                'title' => $page->title,
                'slug' => $page->slug,
                'status' => $page->status,
            ]);

            return $page;
        });
    }
}
