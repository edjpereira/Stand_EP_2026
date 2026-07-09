<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::define('admin-only', function ($user) {
            return $user->role === 'admin';
        });

        Event::listen(Login::class, function ($event) {
        $event->user->update([
            'last_login_at' => Carbon::now()
        ]);
    });
    }
}
