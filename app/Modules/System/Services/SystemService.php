<?php

declare(strict_types=1);

namespace App\Modules\System\Services;

use App\Modules\System\Contracts\SystemServiceInterface;
use App\Modules\System\Actions\GetSettingAction;
use App\Modules\System\Actions\SetSettingAction;
use App\Modules\System\Actions\LogAuditAction;
use Illuminate\Database\Eloquent\Model;

class SystemService implements SystemServiceInterface
{
    public function __construct(
        private GetSettingAction $getSettingAction,
        private SetSettingAction $setSettingAction,
        private LogAuditAction $logAuditAction
    ) {
    }

    public function getSetting(string $key, $default = null): ?string
    {
        return $this->getSettingAction->execute($key, $default);
    }

    public function setSetting(string $key, ?string $value, ?string $description = null): void
    {
        $this->setSettingAction->execute($key, $value, $description);
    }

    public function logAudit(string $action, ?Model $auditable = null, ?array $oldValues = null, ?array $newValues = null): void
    {
        $this->logAuditAction->execute($action, $auditable, $oldValues, $newValues);
    }
}
