<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Modules\CMS\Models\Page;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;

class DeletePageAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {
    }

    public function execute(Page $page): void
    {
        DB::transaction(function () use ($page) {
            $oldValues = [
                'title' => $page->title,
                'slug' => $page->slug,
                'status' => $page->status,
            ];

            // Detach children
            $page->children()->update(['parent_id' => null]);
            $page->delete();

            // Log Audit
            $this->systemService->logAudit('cms.page.delete', $page, $oldValues, null);
        });
    }
}
