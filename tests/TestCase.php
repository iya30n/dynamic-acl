<?php

use Tests\Dependencies\User;

abstract class TestCase extends Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Dependencies/database/migrations');

        $this->artisan('migrate', ['--database' => 'dynamicAcl'])->run();
    }

    protected function getPackageProviders($app)
    {
        return [
            'Iya30n\DynamicAcl\Providers\DynamicAclServiceProvider',
            'Javoscript\MacroableModels\MacroableModelsServiceProvider'
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('dynamicACL', [
            'controller_path' => 'tests/Dependencies'
        ]);

        $app['config']->set('auth', [
            'providers' => [
                'users' => [
                    'driver' => 'eloquent',
                    'model' => User::class
                ]
            ]
        ]);

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'dynamicAcl');
        $app['config']->set('database.connections.dynamicAcl', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Define routes setup.
     *
     * @param  \Illuminate\Routing\Router  $router
     *
     * @return void
     */
    protected function defineRoutes($router)
    {
        $router->get('admin/posts', function () {
            return 'post list';
        })->middleware('DynamicAcl')->name('admin.posts');

        $router->get('admin/posts{post}', function () {
            return 'single post';
        })->name('admin.posts.show');
    }
}
