<?php

declare(strict_types=1);

namespace App\Modules\Media\Actions;

use App\Modules\Media\Models\Media;
use App\Modules\Media\Models\MediaVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadMediaAction
{
    private array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain',
    ];

    /**
     * Upload and process a media file.
     *
     * @param UploadedFile $file
     * @param int|null $folderId
     * @param string|null $caption
     * @param string|null $altText
     * @return Media
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function execute(UploadedFile $file, ?int $folderId = null, ?string $caption = null, ?string $altText = null): Media
    {
        $mimeType = $file->getMimeType();

        if (!in_array($mimeType, $this->allowedMimeTypes, true)) {
            throw new \InvalidArgumentException("Mime type '{$mimeType}' is not allowed.");
        }

        // 1. Generate unique file name
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'bin';
        $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        $hashName = md5($filename . '_' . time() . '_' . uniqid('', true));
        $storageName = "{$hashName}.{$extension}";

        // 2. Save file to public storage disk
        $path = $file->storeAs('media', $storageName, 'public');
        if (!$path) {
            throw new \RuntimeException("Failed to store file '{$originalName}'.");
        }

        // 3. Get image dimensions if applicable
        $width = null;
        $height = null;
        $isImage = str_starts_with($mimeType, 'image/');

        if ($isImage) {
            $imageInfo = @getimagesize($file->getRealPath());
            if ($imageInfo) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];
            }
        }

        // 4. Save media record to DB
        $media = Media::create([
            'folder_id' => $folderId,
            'disk' => 'public',
            'path' => $path,
            'filename' => $storageName,
            'original_name' => $originalName,
            'extension' => $extension,
            'mime_type' => $mimeType,
            'size' => $file->getSize(),
            'width' => $width,
            'height' => $height,
            'alt_text' => $altText ?: $filename,
            'caption' => $caption,
            'uploaded_by' => auth()->id(),
        ]);

        // 5. Generate variants if it is an image
        if ($isImage && $width && $height) {
            $this->generateVariants($media, $file->getRealPath(), $extension);
        }

        return $media;
    }

    /**
     * Generate image variants (thumbnail & medium) using PHP GD.
     *
     * @param Media $media
     * @param string $sourcePath
     * @param string $extension
     * @return void
     */
    private function generateVariants(Media $media, string $sourcePath, string $extension): void
    {
        $mimeType = $media->mime_type;

        // Load the image based on mime type
        $srcImage = null;
        switch ($mimeType) {
            case 'image/jpeg':
                $srcImage = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $srcImage = @imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $srcImage = @imagecreatefromwebp($sourcePath);
                break;
            case 'image/gif':
                $srcImage = @imagecreatefromgif($sourcePath);
                break;
        }

        if (!$srcImage) {
            return;
        }

        $origW = $media->width;
        $origH = $media->height;

        // 1. Generate Thumbnail (150x150 Crop Center)
        $this->createThumbnail($media, $srcImage, $origW, $origH, $extension);

        // 2. Generate Medium (Max width 600, proportional)
        $this->createMediumVariant($media, $srcImage, $origW, $origH, $extension);

        @imagedestroy($srcImage);
    }

    /**
     * Create crop-centered thumbnail.
     */
    private function createThumbnail(Media $media, $srcImage, int $origW, int $origH, string $extension): void
    {
        $thumbSize = 150;
        $thumbImage = imagecreatetruecolor($thumbSize, $thumbSize);

        // Handle transparency
        imagealphablending($thumbImage, false);
        imagesavealpha($thumbImage, true);

        // Calculate crop offset
        $srcX = 0;
        $srcY = 0;
        $srcW = $origW;
        $srcH = $origH;

        if ($origW > $origH) {
            $srcW = $origH;
            $srcX = (int) (($origW - $origH) / 2);
        } elseif ($origH > $origW) {
            $srcH = $origW;
            $srcY = (int) (($origH - $origW) / 2);
        }

        imagecopyresampled($thumbImage, $srcImage, 0, 0, $srcX, $srcY, $thumbSize, $thumbSize, $srcW, $srcH);

        // Save thumbnail file
        $baseName = pathinfo($media->filename, PATHINFO_FILENAME);
        $variantName = "{$baseName}_thumbnail.{$extension}";
        $variantPath = "media/{$variantName}";
        $fullDestPath = Storage::disk('public')->path($variantPath);

        $this->saveImageDisk($thumbImage, $media->mime_type, $fullDestPath);
        @imagedestroy($thumbImage);

        // Store variant in DB
        $fileSize = @filesize($fullDestPath) ?: 0;
        MediaVariant::create([
            'media_id' => $media->id,
            'variant' => 'thumbnail',
            'path' => $variantPath,
            'width' => $thumbSize,
            'height' => $thumbSize,
            'size' => $fileSize,
        ]);
    }

    /**
     * Create proportional medium variant.
     */
    private function createMediumVariant(Media $media, $srcImage, int $origW, int $origH, string $extension): void
    {
        $maxWidth = 600;
        if ($origW <= $maxWidth) {
            // No need to resize, just copy original but store as variant
            $variantName = pathinfo($media->filename, PATHINFO_FILENAME) . "_medium.{$extension}";
            $variantPath = "media/{$variantName}";
            
            Storage::disk('public')->copy($media->path, $variantPath);
            $fullDestPath = Storage::disk('public')->path($variantPath);
            $fileSize = @filesize($fullDestPath) ?: $media->size;

            MediaVariant::create([
                'media_id' => $media->id,
                'variant' => 'medium',
                'path' => $variantPath,
                'width' => $origW,
                'height' => $origH,
                'size' => $fileSize,
            ]);
            return;
        }

        $newW = $maxWidth;
        $newH = (int) (($maxWidth / $origW) * $origH);

        $mediumImage = imagecreatetruecolor($newW, $newH);

        // Handle transparency
        imagealphablending($mediumImage, false);
        imagesavealpha($mediumImage, true);

        imagecopyresampled($mediumImage, $srcImage, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        // Save medium file
        $variantName = pathinfo($media->filename, PATHINFO_FILENAME) . "_medium.{$extension}";
        $variantPath = "media/{$variantName}";
        $fullDestPath = Storage::disk('public')->path($variantPath);

        $this->saveImageDisk($mediumImage, $media->mime_type, $fullDestPath);
        @imagedestroy($mediumImage);

        // Store variant in DB
        $fileSize = @filesize($fullDestPath) ?: 0;
        MediaVariant::create([
            'media_id' => $media->id,
            'variant' => 'medium',
            'path' => $variantPath,
            'width' => $newW,
            'height' => $newH,
            'size' => $fileSize,
        ]);
    }

    /**
     * Helper to output GD image resource to disk.
     */
    private function saveImageDisk($imageResource, string $mimeType, string $destPath): void
    {
        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($imageResource, $destPath, 85);
                break;
            case 'image/png':
                imagepng($imageResource, $destPath, 6);
                break;
            case 'image/webp':
                imagewebp($imageResource, $destPath, 80);
                break;
            case 'image/gif':
                imagegif($imageResource, $destPath);
                break;
        }
    }
}
