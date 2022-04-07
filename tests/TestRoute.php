<?php

use Iya30n\DynamicAcl\Route;
use Tests\Dependencies\User;

class TestRoute extends TestCase
{
	public function testGetUserDefinedRoutes()
	{
		$routes = resolve(Route::class)->getUserDefined();

        dd($routes);
	}
}
