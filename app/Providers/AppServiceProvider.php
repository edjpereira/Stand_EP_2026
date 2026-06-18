<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// CORREÇÃO: O caminho correto para o Paginator é este:
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tranca a paginação no formato do Bootstrap 5
        Paginator::useBootstrapFive();
        // Define a regra para o admin
        Gate::define('admin-only', function ($user) {
            return $user->role === 'admin';
        });
    }
}
