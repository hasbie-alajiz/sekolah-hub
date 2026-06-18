<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Modules\System\Models\Setting;

class SetSettingAction
{
    public function execute(string $key, ?string $value, ?string $description = null): void
    {
        $data = ['value' => $value];
        if ($description !== null) {
            $data['description'] = $description;
        }

        Setting::updateOrCreate(['key' => $key], $data);
    }
}
