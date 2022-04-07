<?php

use Tests\Dependencies\User;

abstract class TestCase extends Orchestra\Testbench\TestCase
{
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
