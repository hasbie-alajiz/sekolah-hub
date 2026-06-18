<?php

declare(strict_types=1);

namespace App\Modules\CMS\Policies;

use App\Models\User;

class PagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('cms.manage');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('cms.manage');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('cms.manage');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('cms.manage');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('cms.manage');
    }
}
