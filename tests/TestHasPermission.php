<?php

use Iya30n\DynamicAcl\Models\Role;

class TestHasPermission extends TestCase
{
	public function test_super_admin_check_access()
	{
		$role = Role::create([
			'name' => 'super_admin',
			'permissions' => ['fullAccess' => 1]
		]);

		$this->admin->roles()->sync($role->id);

		$this->assertTrue($this->admin->hasPermission('admin.posts.index'));
		$this->assertTrue($this->admin->hasPermission('admin.posts.show'));
	}

	public function test_hasPermission_method_on_user_model()
	{
		$role = Role::create([
			'name' => 'access to index',
			'permissions' => ['admin.posts' => ['index' => 1]]
		]);

		$this->admin->roles()->sync($role->id);

		$this->assertTrue($this->admin->hasPermission('admin.posts.index'));
		$this->assertFalse($this->admin->hasPermission('admin.posts.show'));
	}
}
