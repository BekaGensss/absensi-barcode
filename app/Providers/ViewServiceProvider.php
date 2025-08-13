<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
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
        // View Composer untuk 'layouts.app'
        View::composer('layouts.app', function ($view) {
            $settings = Setting::pluck('value', 'key');
            $view->with('settings', $settings);
        });
    }
}