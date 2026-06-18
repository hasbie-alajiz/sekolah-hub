<?php

declare(strict_types=1);

namespace App\Modules\CMS\Providers;

use App\Modules\CMS\Contracts\CMSServiceInterface;
use App\Modules\CMS\Services\CMSService;
use App\Modules\CMS\Models\Post;
use App\Modules\CMS\Models\Page;
use App\Modules\CMS\Models\Category;
use App\Modules\CMS\Models\Menu;
use App\Modules\CMS\Policies\PostPolicy;
use App\Modules\CMS\Policies\PagePolicy;
use App\Modules\CMS\Policies\CategoryPolicy;
use App\Modules\CMS\Policies\MenuPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CMSServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            CMSServiceInterface::class,
            CMSService::class
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'cms');

        // Register Policies
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(Page::class, PagePolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Menu::class, MenuPolicy::class);
    }
}
