<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(\App\Modules\System\Providers\SystemServiceProvider::class);
        $this->app->register(\App\Modules\Media\Providers\MediaServiceProvider::class);
        $this->app->register(\App\Modules\CMS\Providers\CMSServiceProvider::class);
        $this->app->register(\App\Modules\Gallery\Providers\GalleryServiceProvider::class);
        $this->app->register(\App\Modules\Contact\Providers\ContactServiceProvider::class);
        $this->app->register(\App\Modules\Theme\Providers\ThemeServiceProvider::class);
        $this->app->register(\App\Modules\PPDB\Providers\PPDBServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
