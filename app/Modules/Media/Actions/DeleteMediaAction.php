<?php

declare(strict_types=1);

namespace App\Modules\Media\Actions;

use App\Modules\Media\Models\Media;
use Illuminate\Support\Facades\Storage;

class DeleteMediaAction
{
    /**
     * Delete a media file.
     *
     * @param int|Media $media
     * @param bool $forceDelete Set to true for permanent physical file deletion
     * @return bool
     */
    public function execute(int|Media $media, bool $forceDelete = false): bool
    {
        if (is_int($media)) {
            $media = Media::withTrashed()->findOrFail($media);
        }

        if ($forceDelete) {
            // 1. Delete physical variant files
            foreach ($media->variants as $variant) {
                if (Storage::disk('public')->exists($variant->path)) {
                    Storage::disk('public')->delete($variant->path);
                }
                $variant->delete();
            }

            // 2. Delete original file
            if (Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->delete($media->path);
            }

            // 3. Hard delete from database
            return (bool) $media->forceDelete();
        }

        // Default: Soft delete record (keeps physical file on disk)
        return (bool) $media->delete();
    }
}
