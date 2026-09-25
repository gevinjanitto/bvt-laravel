<?php

namespace App\Providers;

<<<<<<< HEAD
use App\Support\CmsText;
use Illuminate\Support\Facades\Blade;
=======
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
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
<<<<<<< HEAD
        Blade::precompiler(function (string $template) {
            $scope = CmsText::scopeOf((string) Blade::getPath());
            return $scope ? CmsText::instrument($template, $scope) : $template;
        });
=======
        //
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
    }
}
