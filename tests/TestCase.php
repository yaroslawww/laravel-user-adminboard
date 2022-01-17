<?php

namespace UserAdmin\Tests;

use Orchestra\Testbench\Database\MigrateProcessor;
use UserAdmin\Facades\UserAdmin;
use UserAdmin\ServiceProvider;
use UserAdmin\Tests\Fixtures\FixturesServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
            FixturesServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();

        $migrator = new MigrateProcessor($this, [
            '--path'     => __DIR__.'/Fixtures/migrations',
            '--realpath' => true,
        ]);
        $migrator->up();
    }

    protected function defineRoutes($router)
    {
        UserAdmin::routes();
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // $app['config']->set('user-adminboard.some_key', 'some_value');
    }
}
