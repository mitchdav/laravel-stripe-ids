<?php

namespace Mitchdav\StripeIds;

use Mitchdav\StripeIds\Generators\GeneratorInterface;

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

        $this->app->bind(GeneratorInterface::class, function ($app) {
            return $this->app->make($app['config']['stripe_ids']['generator']);
        });

        $this->app->singleton(StripeIds::class, function ($app) {
            return new StripeIds(
                $this->app->make(GeneratorInterface::class),
                $app['config']['stripe_ids']['hash_length'],
                $app['config']['stripe_ids']['hash_alphabet']
            );
        });
    }
}