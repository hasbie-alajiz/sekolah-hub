<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Modules\System\Models\Setting;

class GetSettingAction
{
    public function execute(string $key, $default = null): ?string
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : ($default !== null ? (string) $default : null);
    }
}
