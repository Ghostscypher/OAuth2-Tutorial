<?php

namespace App\Providers;

use App\Socialite\Oauth2ServerProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

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
        Socialite::extend('oauth2_server', function($app) {
            $config = $app['config']['services.oauth2_server'];

            $provider = new Oauth2ServerProvider(
                $app['request'],
                $config['client_id'],
                $config['client_secret'],
                URL::to($config['redirect'])
            );

            // Enable PKCE
            $provider->enablePKCE();

            return $provider;
        });
    }
}
