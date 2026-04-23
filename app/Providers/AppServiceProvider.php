<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Utiliser le style Bootstrap pour la pagination
        Paginator::useBootstrapFive();
        Schema::defaultStringLength(191);
    }
}
