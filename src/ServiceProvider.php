<?php

namespace Mitchdav\StripeIds;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/stripe-ids.php', 'stripe-ids'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/stripe-ids.php' => config_path('stripe-ids.php'),
        ], 'config');

        $this->app->singleton(StripeIds::class, function ($app) {
            return new StripeIds(
                $app['config']['stripe-ids']['alphabet'],
                $app['config']['stripe-ids']['length'],
                $app['config']['stripe-ids']['separator'],
                $app['config']['stripe-ids']['prefixes']
            );
        });
    }
}