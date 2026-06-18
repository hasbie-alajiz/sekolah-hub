<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateUserAction
{
    private LogAuditAction $logAuditAction;

    public function __construct(LogAuditAction $logAuditAction)
    {
        $this->logAuditAction = $logAuditAction;
    }

    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Assign role
            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            }

            // Log audit
            $this->logAuditAction->execute('user.create', $user, null, [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $data['role'] ?? null,
            ]);

            return $user;
        });
    }
}
