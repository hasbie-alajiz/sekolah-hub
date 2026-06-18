<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DeleteUserAction
{
    private LogAuditAction $logAuditAction;

    public function __construct(LogAuditAction $logAuditAction)
    {
        $this->logAuditAction = $logAuditAction;
    }

    public function execute(User $user): void
    {
        if (auth()->id() === $user->id) {
            throw ValidationException::withMessages([
                'user' => ['Anda tidak dapat menghapus akun Anda sendiri.'],
            ]);
        }

        DB::transaction(function () use ($user) {
            $oldValues = [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->roles->first()?->name,
            ];

            $user->syncRoles([]);
            $user->delete();

            // Log audit
            $this->logAuditAction->execute('user.delete', $user, $oldValues, null);
        });
    }
}
