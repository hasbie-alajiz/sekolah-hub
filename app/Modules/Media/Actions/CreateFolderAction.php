<?php

declare(strict_types=1);

namespace App\Modules\Media\Actions;

use App\Modules\Media\Models\MediaFolder;
use Illuminate\Support\Str;

class CreateFolderAction
{
    /**
     * Create a virtual folder.
     *
     * @param string $name
     * @param int|null $parentId
     * @return MediaFolder
     */
    public function execute(string $name, ?int $parentId = null): MediaFolder
    {
        $slug = Str::slug($name);

        // Ensure slug is unique at the current parent level
        $originalSlug = $slug;
        $count = 1;
        while (MediaFolder::where('parent_id', $parentId)->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . (++$count);
        }

        return MediaFolder::create([
            'name' => $name,
            'slug' => $slug,
            'parent_id' => $parentId,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);
    }
}
