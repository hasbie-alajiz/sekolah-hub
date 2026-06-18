<?php

declare(strict_types=1);

namespace App\Modules\System\Policies;

use App\Models\User;

class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('settings.manage');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('settings.manage');
    }
}
