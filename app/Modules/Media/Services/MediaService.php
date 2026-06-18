<?php

declare(strict_types=1);

namespace App\Modules\Media\Services;

use App\Modules\Media\Contracts\MediaServiceInterface;
use App\Modules\Media\Models\Media;
use App\Modules\Media\Actions\UploadMediaAction;
use App\Modules\Media\Actions\DeleteMediaAction;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaService implements MediaServiceInterface
{
    public function __construct(
        protected UploadMediaAction $uploadMediaAction,
        protected DeleteMediaAction $deleteMediaAction
    ) {}

    /**
     * @inheritDoc
     */
    public function upload(UploadedFile $file, ?int $folderId = null, ?string $caption = null, ?string $altText = null): Media
    {
        return $this->uploadMediaAction->execute($file, $folderId, $caption, $altText);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $mediaId): bool
    {
        return $this->deleteMediaAction->execute($mediaId);
    }

    /**
     * @inheritDoc
     */
    public function getUrl(int $mediaId, ?string $variant = null): string
    {
        $media = Media::withTrashed()->findOrFail($mediaId);

        if ($variant) {
            $variantModel = $media->variants()->where('variant', $variant)->first();
            if ($variantModel) {
                return Storage::disk('public')->url($variantModel->path);
            }
        }

        return Storage::disk('public')->url($media->path);
    }

    /**
     * @inheritDoc
     */
    public function getPath(int $mediaId, ?string $variant = null): string
    {
        $media = Media::withTrashed()->findOrFail($mediaId);

        if ($variant) {
            $variantModel = $media->variants()->where('variant', $variant)->first();
            if ($variantModel) {
                return Storage::disk('public')->path($variantModel->path);
            }
        }

        return Storage::disk('public')->path($media->path);
    }
}
