<?php

namespace App\Providers;

use App\Support\CmsText;
use Illuminate\Support\Facades\Blade;
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
        Blade::precompiler(function (string $template) {
            $scope = CmsText::scopeOf((string) Blade::getPath());
            return $scope ? CmsText::instrument($template, $scope) : $template;
        });
    }
}
