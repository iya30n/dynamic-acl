<?php

namespace Iya30n\DynamicAcl\Providers;

use Illuminate\Support\ServiceProvider;
use Iya30n\DynamicAcl\Models\Role;
use \Javoscript\MacroableModels\Facades\MacroableModels;

class DynamicAclServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'dynamicACL');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        $this->publishes([
            __DIR__ . '/../../config/dynamicACL.php' => config_path('dynamicACL.php'),
            __DIR__ . '/../../database/migrations' => database_path('migrations')
        ]);

        $this->registerMacros();
    }

    private function registerMacros()
    {
        MacroableModels::addMacro(config('auth.providers.users.model'), 'roles', function() {
            return $this->belongsToMany(Role::class);
        });

        MacroableModels::addMacro(config('auth.providers.users.model'), 'hasPermission', function ($access) {
            if ($access == '' || $access == 'dashboard') return true;

            foreach ($this->roles as $role) {
                $userPermissions = (strpos($access, '.') != false) ?
                    array_dot($role->permissions) :
                    $role->permissions;

                return isset($userPermissions[$access]) || isset($userPermissions['fullAccess']);
            }

            return false;
        });
    }
}
