<?php

namespace Mitchdav\StripeIds\Tests;

use Illuminate\Database\Schema\Blueprint;
use Mitchdav\StripeIds\Facade;
use Mitchdav\StripeIds\ServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function setUpDatabase($app)
    {
        $app['db']
            ->connection()
            ->getSchemaBuilder()
            ->create('test_models', function (Blueprint $table) {
                $table->string('id');
                $table->timestamps();
            });
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'StripeIds' => Facade::class,
        ];
    }
}