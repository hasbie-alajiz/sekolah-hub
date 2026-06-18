<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UpdateUserAction
{
    private LogAuditAction $logAuditAction;

    public function __construct(LogAuditAction $logAuditAction)
    {
        $this->logAuditAction = $logAuditAction;
    }

    public function execute(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $oldValues = [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->first()?->name,
            ];

            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            if (isset($data['role'])) {
                $user->syncRoles([$data['role']]);
            }

            // Log audit
            $this->logAuditAction->execute('user.update', $user, $oldValues, [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $data['role'] ?? null,
            ]);

            return $user;
        });
    }
}
