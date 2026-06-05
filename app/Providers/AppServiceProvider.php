<?php

namespace App\Providers;

use App\Enums\DocumentType;
use Illuminate\Support\Facades\Route;
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
        Route::bind('type', function ($value) {
            return DocumentType::tryFrom((int) $value) ?? abort(404);
        });
    }
}
