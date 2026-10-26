<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // URL gambar relatif dan ikut subfolder tempat aplikasi dipasang (mis. /myrubel/storage/...)
        config(['filesystems.disks.public.url' => request()->getBaseUrl().'/storage']);
    }
}
