<?php

use Iya30n\DynamicAcl\Http\Middleware\Admin;
use Iya30n\DynamicAcl\Http\Middleware\Authorize;
use Tests\Dependencies\Post;
use Tests\Dependencies\User;

abstract class TestCase extends Orchestra\Testbench\TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Dependencies/database/migrations');

        $this->artisan('migrate', ['--database' => 'dynamicAcl'])->run();

        $this->createAdmin();
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
            'defaults' => [
                'guard' => 'web',
                'passwords' => 'users',
            ],
            'guards' => [
                'web' => [
                    'driver' => 'session',
                    'provider' => 'users',
                ],
            ],
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
        $router->bind('post', function($post) {
            return Post::find($post);
        });

        $router->get('admin/posts', function () {
            return 'post list';
        })->middleware(Admin::class)->name('admin.posts.index');

        $router->get('admin/posts/{post}', function (Post $post) {
            return $post->title;
        })->middleware([\Illuminate\Routing\Middleware\SubstituteBindings::class, Admin::class, Authorize::class])->name('admin.posts.show');
    }

    private function createAdmin()
    {
        $this->admin = User::create([
			'name' => 'test', 
			'email' => 'test@gmail.com', 
			'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' // password
		]);
    }
}
