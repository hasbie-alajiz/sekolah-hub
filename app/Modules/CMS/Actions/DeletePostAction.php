<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Modules\CMS\Models\Post;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;

class DeletePostAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {
    }

    public function execute(Post $post): void
    {
        DB::transaction(function () use ($post) {
            $oldValues = [
                'title' => $post->title,
                'slug' => $post->slug,
                'status' => $post->status,
            ];

            $post->delete();

            // Log Audit
            $this->systemService->logAudit('cms.post.delete', $post, $oldValues, null);
        });
    }
}
