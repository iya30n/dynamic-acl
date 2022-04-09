<?php

use Iya30n\DynamicAcl\ACL;
use Iya30n\DynamicAcl\Route;

class TestAclFacade extends TestCase
{
	public function test_if_getRoutes_method_on_AclFacade_returns_correct_data()
	{
		$routes = ACL::getRoutes();

		$this->assertArrayHasKey('admin.roles', $routes);
		$this->assertArrayHasKey('admin', $routes);

		$this->assertIsArray($routes['admin.roles']);
		$this->assertIsArray($routes['admin']);

		foreach($routes['admin.roles'] as $route) {
			$this->assertEquals(array_keys($route), ['uri', 'name', 'action', 'middleware']);
		}

		foreach($routes['admin'] as $route) {
			$this->assertEquals(array_keys($route), ['uri', 'name', 'action', 'middleware']);
		}
	}
}
