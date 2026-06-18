<?php

declare(strict_types=1);

namespace App\Modules\CMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $table = 'pages';

    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'content',
        'featured_media_id',
        'status',
        'seo_title',
        'seo_description',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    /**
     * Resolve featured image URL using MediaServiceInterface.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (!$this->featured_media_id) {
            return null;
        }

        try {
            $mediaService = app(\App\Modules\Media\Contracts\MediaServiceInterface::class);
            return $mediaService->getUrl($this->featured_media_id);
        } catch (\Exception $e) {
            return null;
        }
    }
}
