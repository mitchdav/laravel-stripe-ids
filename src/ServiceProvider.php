<?php

namespace Mitchdav\StripeIds;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/stripe_ids.php', 'stripe_ids'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/stripe_ids.php' => config_path('stripe_ids.php'),
        ], 'config');

        $this->app->singleton(StripeIds::class, function ($app) {
            return new StripeIds(
                $app['config']['stripe_ids']['hash_length'],
                $app['config']['stripe_ids']['hash_alphabet'],
                $app['config']['stripe_ids']['prefixes']
            );
        });
    }
}