<?php

declare(strict_types=1);

namespace App\Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'description',
    ];
}
