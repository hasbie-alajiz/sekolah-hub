<?php

declare(strict_types=1);

namespace App\Modules\System\Policies;

use App\Models\User;

class AuditLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('audit_logs.view');
    }
}
