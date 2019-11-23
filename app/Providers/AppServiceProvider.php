<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\ServerObserver;
use App\Observers\PasswordObserver;
use App\Server;
use App\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Password::observe(PasswordObserver::class);
        Server::observe(ServerObserver::class);
    }
}
