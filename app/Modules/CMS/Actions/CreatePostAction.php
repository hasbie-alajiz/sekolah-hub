<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Modules\CMS\Models\Post;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreatePostAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {
    }

    public function execute(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
            
            // Handle unique slug
            $originalSlug = $slug;
            $count = 1;
            while (Post::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }

            $post = Post::create([
                'title' => $data['title'],
                'slug' => $slug,
                'excerpt' => $data['excerpt'] ?? null,
                'content' => \App\Modules\System\Support\HtmlSanitizer::clean($data['content']),
                'featured_media_id' => !empty($data['featured_media_id']) ? (int) $data['featured_media_id'] : null,
                'status' => $data['status'] ?? 'draft',
                'published_at' => ($data['status'] ?? 'draft') === 'published' ? now() : null,
                'author_id' => auth()->id(),
                'seo_title' => $data['seo_title'] ?? null,
                'seo_description' => $data['seo_description'] ?? null,
            ]);

            if (!empty($data['categories'])) {
                $post->categories()->sync($data['categories']);
            }

            // Log Audit
            $this->systemService->logAudit('cms.post.create', $post, null, [
                'title' => $post->title,
                'slug' => $post->slug,
                'status' => $post->status,
            ]);

            return $post;
        });
    }
}
