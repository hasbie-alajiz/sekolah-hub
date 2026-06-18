<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Modules\CMS\Models\Category;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;

class DeleteCategoryAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {
    }

    public function execute(Category $category): void
    {
        DB::transaction(function () use ($category) {
            $oldValues = [
                'name' => $category->name,
                'slug' => $category->slug,
            ];

            // Detach children
            $category->children()->update(['parent_id' => null]);
            // Detach from posts
            $category->posts()->detach();
            $category->delete();

            // Log Audit
            $this->systemService->logAudit('cms.category.delete', $category, $oldValues, null);
        });
    }
}
