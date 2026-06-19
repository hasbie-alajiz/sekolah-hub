<?php

declare(strict_types=1);

namespace App\Modules\CMS\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;

class Post extends Model
{
    use SoftDeletes, HasRichText;

    protected $table = 'posts';

    protected $richTextAttributes = [
        'content' => ['attribute' => true],
    ];

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_media_id',
        'status',
        'published_at',
        'author_id',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_categories', 'post_id', 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
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
