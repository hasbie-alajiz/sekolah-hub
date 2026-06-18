<?php

declare(strict_types=1);

namespace App\Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaVariant extends Model
{
    protected $table = 'media_variants';

    protected $fillable = [
        'media_id',
        'variant',
        'path',
        'width',
        'height',
        'size',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
