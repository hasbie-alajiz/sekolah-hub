<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Modules\CMS\Models\Page;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdatePageAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {
    }

    public function execute(Page $page, array $data): Page
    {
        return DB::transaction(function () use ($page, $data) {
            $oldValues = [
                'title' => $page->title,
                'slug' => $page->slug,
                'status' => $page->status,
            ];

            $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
            
            // Handle unique slug
            if ($slug !== $page->slug) {
                $originalSlug = $slug;
                $count = 1;
                while (Page::where('slug', $slug)->where('id', '!=', $page->id)->exists()) {
                    $slug = "{$originalSlug}-{$count}";
                    $count++;
                }
            }

            $page->update([
                'parent_id' => !empty($data['parent_id']) ? (int) $data['parent_id'] : null,
                'title' => $data['title'],
                'slug' => $slug,
                'content' => $data['content'],
                'featured_media_id' => !empty($data['featured_media_id']) ? (int) $data['featured_media_id'] : null,
                'status' => $data['status'] ?? $page->status,
                'seo_title' => $data['seo_title'] ?? null,
                'seo_description' => $data['seo_description'] ?? null,
            ]);

            // Log Audit
            $this->systemService->logAudit('cms.page.update', $page, $oldValues, [
                'title' => $page->title,
                'slug' => $page->slug,
                'status' => $page->status,
            ]);

            return $page;
        });
    }
}
