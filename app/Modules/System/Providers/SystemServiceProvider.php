<?php

declare(strict_types=1);

namespace App\Modules\System\Providers;

use App\Modules\System\Contracts\SystemServiceInterface;
use App\Modules\System\Services\SystemService;
use App\Modules\System\Models\Setting;
use App\Modules\System\Models\AuditLog;
use App\Models\User;
use App\Modules\System\Policies\SettingPolicy;
use App\Modules\System\Policies\UserPolicy;
use App\Modules\System\Policies\AuditLogPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class SystemServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            SystemServiceInterface::class,
            SystemService::class
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'system');

        // Register Policies
        Gate::policy(Setting::class, SettingPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(AuditLog::class, AuditLogPolicy::class);

        // Super Admin Gate Bypass
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
