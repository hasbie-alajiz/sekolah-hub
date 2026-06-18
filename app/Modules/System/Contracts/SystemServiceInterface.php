<?php

declare(strict_types=1);

namespace App\Modules\System\Contracts;

use Illuminate\Database\Eloquent\Model;

interface SystemServiceInterface
{
    /**
     * Get a global setting value.
     *
     * @param string $key
     * @param mixed $default
     * @return string|null
     */
    public function getSetting(string $key, $default = null): ?string;

    /**
     * Set a global setting value.
     *
     * @param string $key
     * @param string|null $value
     * @param string|null $description
     * @return void
     */
    public function setSetting(string $key, ?string $value, ?string $description = null): void;

    /**
     * Write an audit log entry.
     *
     * @param string $action
     * @param Model|null $auditable
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return void
     */
    public function logAudit(string $action, ?Model $auditable = null, ?array $oldValues = null, ?array $newValues = null): void;
}
