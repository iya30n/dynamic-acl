<?php

namespace Iya30n\DynamicAcl\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Iya30n\DynamicAcl\ACL;
use Iya30n\DynamicAcl\Models\Role;
use Illuminate\Support\ServiceProvider;
use Iya30n\DynamicAcl\Http\Middleware\{Admin, Authorize};
use Iya30n\DynamicAcl\Console\Commands\MakeAdmin;
use Iya30n\DynamicAcl\Separators\Separator;
use \Javoscript\MacroableModels\Facades\MacroableModels;

class DynamicAclServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->setSeparatorDriver();

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'dynamicACL');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'dynamicACL');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        $this->publishes([
            __DIR__ . '/../../config/dynamicACL.php' => config_path('dynamicACL.php'),
            __DIR__ . '/../../database/migrations' => database_path('migrations')
        ]);

        $this->registerMacros();

        $this->registerMiddlewares();

        $this->registerCommands();

        $this->registerViewComposers();
    }

    protected function setSeparatorDriver()
    {
        $this->app->bind('acl_handler', Separator::class);
    }

    private function registerMacros()
    {
        $authModel = config('auth.providers.users.model');

         MacroableModels::addMacro($authModel, 'roles', function () {
             return $this->belongsToMany(Role::class);
        });

        MacroableModels::addMacro($authModel, 'hasPermission', function ($access) {
            if (in_array($access, config('dynamicACL.ignore_list'))) return true;

            foreach ($this->roles()->get() as $role) {
                return ACL::checkAccess($access, $role->permissions);
            }

            return false;
        });
    }

    private function registerMiddlewares()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('dynamicAcl', Admin::class);
        $router->aliasMiddleware('authorize', Authorize::class);
    }

    private function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeAdmin::class,
            ]);
        }
    }

    protected function registerViewComposers()
    {
       View::composer('dynamicACL::layout', function($view) {
           $view->with([
               'alignment' => config('dynamicACL.alignment', 'ltr')
           ]);
       });
    }
}
