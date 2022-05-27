<?php

use Iya30n\DynamicAcl\Models\Role;
use Tests\Dependencies\Post;

class TestAuthorizeMiddleware extends TestCase
{
	public function test_full_access_admin_should_see_any_pages()
	{
		$role = Role::create([
			'name' => 'full_access',
			'permissions' => [
				'fullAccess' => 1
			]
		]);

		$this->admin->roles()->sync($role->id);

		$post = Post::create([
			'user_id' => $this->admin->id,
			'title' => 'first post',
			'content' => 'first post'
		]);

		$this->actingAs($this->admin)->get('/admin/posts')->assertOk();
		$this->actingAs($this->admin)->get('/admin/posts/' . $post->id)->assertOk();
	}

	public function test_admin_should_see_his_own_posts()
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

		$this->actingAs($this->admin)->get('/admin/posts')->assertOk();
		$this->actingAs($this->admin)->get('/admin/posts/' . $firstPost->id)->assertOk();
		$this->actingAs($this->admin)->get('/admin/posts/' . $secondPost->id)->assertRedirect('/');
	}
}
