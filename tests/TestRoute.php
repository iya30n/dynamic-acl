<?php

use Iya30n\DynamicAcl\Route;

class TestRoute extends TestCase
{
	public function test_if_getUserDefined_method_returns_collection()
	{
		$routes = resolve(Route::class)->getUserDefined();

		$this->assertEquals(get_class($routes), "Illuminate\Support\Collection");
	}

	public function test_if_routes_have_correct_keys()
	{
		$routes = resolve(Route::class)->getUserDefined();

		foreach($routes as $route) {
			$this->assertEquals(array_keys($route), ['uri', 'name', 'action', 'middleware']);
		}
	}
}
