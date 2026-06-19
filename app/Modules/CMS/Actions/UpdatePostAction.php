<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Modules\CMS\Models\Post;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdatePostAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {
    }

    public function execute(Post $post, array $data): Post
    {
        return DB::transaction(function () use ($post, $data) {
            $oldValues = [
                'title' => $post->title,
                'slug' => $post->slug,
                'status' => $post->status,
            ];

            $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
            
            // Handle unique slug
            if ($slug !== $post->slug) {
                $originalSlug = $slug;
                $count = 1;
                while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                    $slug = "{$originalSlug}-{$count}";
                    $count++;
                }
            }

            $publishedAt = $post->published_at;
            if (isset($data['status'])) {
                if ($data['status'] === 'published' && $post->status !== 'published') {
                    $publishedAt = now();
                } elseif ($data['status'] !== 'published') {
                    $publishedAt = null;
                }
            }

            $post->update([
                'title' => $data['title'],
                'slug' => $slug,
                'excerpt' => $data['excerpt'] ?? null,
                'content' => \App\Modules\System\Support\HtmlSanitizer::clean($data['content']),
                'featured_media_id' => !empty($data['featured_media_id']) ? (int) $data['featured_media_id'] : null,
                'status' => $data['status'] ?? $post->status,
                'published_at' => $publishedAt,
                'seo_title' => $data['seo_title'] ?? null,
                'seo_description' => $data['seo_description'] ?? null,
            ]);

            if (isset($data['categories'])) {
                $post->categories()->sync($data['categories']);
            }

            // Log Audit
            $this->systemService->logAudit('cms.post.update', $post, $oldValues, [
                'title' => $post->title,
                'slug' => $post->slug,
                'status' => $post->status,
            ]);

            return $post;
        });
    }
}
