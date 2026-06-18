<?php

declare(strict_types=1);

namespace App\Modules\Media\Contracts;

use Illuminate\Http\UploadedFile;
use App\Modules\Media\Models\Media;

interface MediaServiceInterface
{
    /**
     * Upload an uploaded file to the media storage.
     *
     * @param UploadedFile $file
     * @param int|null $folderId
     * @param string|null $caption
     * @param string|null $altText
     * @return Media
     */
    public function upload(UploadedFile $file, ?int $folderId = null, ?string $caption = null, ?string $altText = null): Media;

    /**
     * Soft delete a media by its ID.
     *
     * @param int $mediaId
     * @return bool
     */
    public function delete(int $mediaId): bool;

    /**
     * Get the URL for a media file or its variant.
     *
     * @param int $mediaId
     * @param string|null $variant
     * @return string
     */
    public function getUrl(int $mediaId, ?string $variant = null): string;

    /**
     * Get the absolute path on filesystem for a media file or its variant.
     *
     * @param int $mediaId
     * @param string|null $variant
     * @return string
     */
    public function getPath(int $mediaId, ?string $variant = null): string;
}
