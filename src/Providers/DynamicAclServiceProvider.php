<?php

namespace Iya30n\DynamicAcl\Providers;

use Illuminate\Support\ServiceProvider;

class DynamicAclServiceProvider extends ServiceProvider
{
	public function boot()
	{
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'dynamicACL');

        $this->publishes([
            __DIR__.'/../../config/dynamicACL.php' => config_path('dynamicACL.php'),
            __DIR__.'/../../database/migrations' => database_path('migrations')
        ]);
	}
}