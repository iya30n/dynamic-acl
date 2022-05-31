<?php

use Iya30n\DynamicAcl\Models\Role;
use Tests\Dependencies\Post;

class TestisOwner extends TestCase
{
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

		$this->assertTrue($this->admin->isOwner($firstPost));
		
		$this->assertFalse($this->admin->isOwner($secondPost));

		$this->expectException(Exception::class);

		$this->assertFalse($this->admin->isOwner($firstPost, 'something_else_id'));
	}

    public function test_if_user_has_access_to_specific_post_array_param()
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

        $this->assertTrue($this->admin->isOwner([Post::class => $firstPost->id]));

        $this->assertFalse($this->admin->isOwner([Post::class => $secondPost->id]));

		$this->expectException(Exception::class);

        $this->assertFalse($this->admin->isOwner([Post::class => $firstPost->id], 'something_else_id'));
    }
}
