<?php

declare(strict_types=1);

namespace App\Modules\CMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $table = 'menu_items';

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'type', // 'custom', 'page', 'post', 'category'
        'reference_type',
        'reference_id',
        'url',
        'target', // '_self', '_blank'
        'sort_order',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Resolve the actual URL based on menu item type.
     */
    public function getUrlAttribute(): string
    {
        switch ($this->type) {
            case 'page':
                $page = Page::find($this->reference_id);
                return $page ? route('public.pages.show', $page->slug) : '#';
            case 'post':
                $post = Post::find($this->reference_id);
                return $post ? route('public.posts.show', $post->slug) : '#';
            case 'category':
                $category = Category::find($this->reference_id);
                return $category ? route('public.categories.show', $category->slug) : '#';
            case 'custom':
            default:
                return $this->attributes['url'] ?: '#';
        }
    }
}
