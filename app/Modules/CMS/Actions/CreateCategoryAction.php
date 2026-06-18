<?php

declare(strict_types=1);

namespace App\Modules\CMS\Actions;

use App\Modules\CMS\Models\Category;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateCategoryAction
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {
    }

    public function execute(array $data): Category
    {
        return DB::transaction(function () use ($data) {
            $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);
            
            // Handle unique slug
            $originalSlug = $slug;
            $count = 1;
            while (Category::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }

            $category = Category::create([
                'parent_id' => !empty($data['parent_id']) ? (int) $data['parent_id'] : null,
                'name' => $data['name'],
                'slug' => $slug,
                'description' => $data['description'] ?? null,
            ]);

            // Log Audit
            $this->systemService->logAudit('cms.category.create', $category, null, [
                'name' => $category->name,
                'slug' => $category->slug,
            ]);

            return $category;
        });
    }
}
