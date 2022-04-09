<?php

use Tests\Dependencies\User;

class TestHasPermission extends TestCase
{
	private $user;

	public function setUp(): void
	{
		parent::setUp();

		$this->user = $this->makeUser();
	}

	private function makeUser($id = 2, $name = 'sina')
	{
		return User::make(['id' => $id, 'name' => $name]);
	}

	public function testIfUserHasAccess()
	{
		/* $authModel = config('auth.providers.users.model');

		dd($authModel->find(1)); */

		/* $fakeUser = User::factory(1)->make();

		dd($fakeUser); */

		// dd($this->user->name);

		$this->assertTrue(true);
	}
}
