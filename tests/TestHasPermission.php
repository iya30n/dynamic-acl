<?php

use Iya30n\DynamicAcl\Models\Role;
use Tests\Dependencies\Post;

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
			'permissions' => [
				'admin.posts' => [
					'index' => 1
				]
			]
		]);

		$this->admin->roles()->sync($role->id);

        $this->assertTrue($this->admin->hasPermission('admin.posts.index'));
		$this->assertFalse($this->admin->hasPermission('admin.posts.show'));
	}

	public function test_if_user_has_access_to_specific_post()
	{
		$role = Role::create([
			'name' => 'access to index',
			'permissions' => [
				'admin.posts' => [
					'index' => 1,
					'show' => 1
				]
			]
		]);

		$this->admin->roles()->sync($role->id);

		$firstPost = Post::create([
			'user_id' => $this->admin->id,
			'title' => 'first post',
			'content' => 'first post'
		]);

		$secondPost = Post::create([
			'user_id' => 9,
			'title' => 'second post',
			'content' => 'second post'
		]);

		$this->assertTrue($this->admin->hasPermission('admin.posts.show', $firstPost));
		
		$this->assertFalse($this->admin->hasPermission('admin.posts.show', $secondPost));

		$this->expectException(Exception::class);

		$this->admin->hasPermission('admin.posts.show', $firstPost, 'something_else_id');
	}
}
