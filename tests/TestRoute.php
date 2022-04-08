<?php

use Iya30n\DynamicAcl\Route;

class TestRoute extends TestCase
{
	public function testGetUserDefinedRoutes()
	{
		$routes = resolve(Route::class)->getUserDefined();

		// dd($routes);

		$this->assertEquals(get_class($routes), "Illuminate\Support\Collection");
	}
}
