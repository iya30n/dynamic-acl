<?php

namespace Iya30n\DynamicAcl\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Iya30n\DynamicAcl\ACL;
use Iya30n\DynamicAcl\Models\Role;
use Illuminate\Support\ServiceProvider;
use Iya30n\DynamicAcl\Http\Middleware\{Admin, Authorize};
use Iya30n\DynamicAcl\Console\Commands\MakeAdmin;
use Iya30n\DynamicAcl\Separators\Separator;
use \Javoscript\MacroableModels\Facades\MacroableModels;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class DynamicAclServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->setSeparatorDriver();

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'dynamicACL');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'dynamicACL');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        $this->publishes([
            __DIR__ . '/../../config/dynamicACL.php' => config_path('dynamicACL.php'),
            
            __DIR__ . '/../../database/migrations/2019_10_03_999999_create_roles_table.php' => base_path('/database/migrations/' . date('Y_m_d') . '_999999_create_roles_table.php'),
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

        throw_if(!class_exists($authModel), new \Exception("The class \"$authModel\" does not exists"));

        /* MacroableModels::addMacro($authModel, 'roles', function () {
             return $this->belongsToMany(Role::class);
        }); */

        $authModel::resolveRelationUsing('roles', function ($userModel) {
            return $userModel->belongsToMany(Role::class);
        });

        MacroableModels::addMacro($authModel, 'isOwner', function ($entity, $foreignKey = null) {
            if ( ! $entity instanceof Model && ! is_array($entity)) return true;

            if (is_array($entity)) {
                $entityName = array_key_first($entity);
                $modelNamespace = "\\App\\Models\\" . ucfirst($entityName);

                if (! class_exists($modelNamespace)) return true;

                $entity = app($modelNamespace)->findOrFail($entity[$entityName]);
            }

            $relationId = $entity->getOriginal(
                $foreignKey ?? $this->getForeignKey()
            );

            return $relationId == $this->getOriginal($this->getKeyName());
        });

        MacroableModels::addMacro($authModel, 'hasPermission', function (string $access, $entity = null, $foreignKey = null) {
            if (in_array($access, config('dynamicACL.ignore_list', []))) return true;

            if( ! $this->allRoles)
                $this->allRoles = $this->roles()->get();

            $hasAccess = false;
            foreach ($this->allRoles as $role) {
                if (ACL::checkAccess('fullAccess', $role->permissions)) return true;
                
                $hasAccess = ACL::checkAccess($access, $role->permissions);

                if ($hasAccess) break;
            }

            return $hasAccess && $this->isOwner($entity, $foreignKey);
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
