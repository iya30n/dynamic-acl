<?php

use Iya30n\DynamicAcl\ACL;
use Iya30n\DynamicAcl\Models\Role;
use Iya30n\DynamicAcl\Route;

class TestAclFacade extends TestCase
{
	public function test_if_getRoutes_method_on_AclFacade_returns_correct_data()
	{
		$routes = ACL::getRoutes();

		$this->assertArrayHasKey('admin.roles', $routes);
		$this->assertArrayHasKey('admin.posts', $routes);

		$this->assertIsArray($routes['admin.roles']);
		$this->assertIsArray($routes['admin.posts']);

		foreach ($routes['admin.roles'] as $route) {
			$this->assertEquals(array_keys($route), ['uri', 'name', 'action', 'middleware']);
		}

		foreach ($routes['admin.posts'] as $route) {
			$this->assertEquals(array_keys($route), ['uri', 'name', 'action', 'middleware']);
		}
	}

	public function test_checkAccess_method_on_AclFacade()
	{
		$role = Role::create([
			'name' => 'super_admin',
			'permissions' => ['fullAccess' => 1]
		]);

		$this->admin->roles()->sync($role->id);

		$routes = resolve(Route::class)->getUserDefined();
		$adminRoles = $this->admin->roles()->get();

		foreach ($routes as $route) {
			foreach ($adminRoles as $role) {
				$this->assertTrue(ACL::checkAccess($route['name'], $role->permissions));
			}
		}
	}
}
