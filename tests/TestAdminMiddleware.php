<?php

use Iya30n\DynamicAcl\Models\Role;
use Tests\Dependencies\Post;

class TestAdminMiddleware extends TestCase
{
	public function test_guest_user_should_redirect()
	{
		$this->get('admin/posts')->assertRedirect('/login');
	}

	public function test_user_is_not_admin_should_redirect()
	{
		$user = $this->admin;

		$this->actingAs($user)->get('/admin/posts')->assertRedirect();
	}

	public function test_admin_has_access_to_a_page()
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

		$post = Post::create([
			'user_id' => $this->admin->id,
			'title' => 'first post',
			'content' => 'first post'
		]);

		$this->actingAs($this->admin)->get('/admin/posts')->assertOk();
		$this->actingAs($this->admin)->get('/admin/posts/' . $post->id)->assertRedirect();
	}
}
