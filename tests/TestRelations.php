<?php

use Iya30n\DynamicAcl\ACL;
use Iya30n\DynamicAcl\Models\Role;
use Iya30n\DynamicAcl\Route;
use Tests\Dependencies\User;

class TestRelations extends TestCase
{
	public function test_user_roles_relation()
	{
		$role = Role::create([
			'name' => 'super_admin',
			'permissions' => ['fullAccess' => 1]
		]);

		$this->admin->roles()->sync($role->id);

		$this->assertInstanceOf(Role::class, $this->admin->roles()->first());
	}

	public function test_role_users_relation()
	{
		
		$role = Role::create([
			'name' => 'super_admin',
			'permissions' => ['fullAccess' => 1]
		]);

		$this->admin->roles()->sync($role->id);
		
		$this->assertInstanceOf(User::class, $role->users()->first());
	}
}
