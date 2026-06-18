<?php

declare(strict_types=1);

namespace App\Modules\System\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('users.manage');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo('users.manage');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('users.manage');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('users.manage');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('users.manage') && $user->id !== $model->id;
    }
}
