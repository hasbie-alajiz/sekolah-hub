<?php

declare(strict_types=1);

namespace App\Modules\System\Actions;

use App\Modules\System\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class LogAuditAction
{
    /**
     * Log an administrative activity.
     *
     * @param string $action
     * @param Model|null $auditable
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return void
     */
    public function execute(string $action, ?Model $auditable = null, ?array $oldValues = null, ?array $newValues = null): void
    {
        // Mask sensitive parameters in values
        $oldValues = $this->maskSensitiveData($oldValues);
        $newValues = $this->maskSensitiveData($newValues);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'auditable_type' => $auditable ? get_class($auditable) : null,
            'auditable_id' => $auditable ? $auditable->getKey() : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Mask sensitive fields.
     */
    private function maskSensitiveData(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        $sensitiveFields = ['password', 'password_confirmation', 'token', 'secret', 'key', 'credential', 'cookie', 'session'];

        foreach ($values as $key => $value) {
            foreach ($sensitiveFields as $field) {
                if (str_contains(strtolower((string) $key), $field)) {
                    $values[$key] = '********';
                }
            }
        }

        return $values;
    }
}
