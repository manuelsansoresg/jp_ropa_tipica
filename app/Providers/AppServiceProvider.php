<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        $settings = Schema::hasTable('site_settings') ? SiteSetting::pluck('value', 'key') : collect();
        View::share('siteSettings', $settings);
        View::composer(['components.header', 'components.footer'], function ($view): void {
            $view->with('menuCategories', Schema::hasTable('categories')
                ? Category::where('active', true)->orderBy('sort_order')->orderBy('name')->get()
                : collect());
        });
    }
}
