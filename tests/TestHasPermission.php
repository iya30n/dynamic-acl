<?php

use Tests\Dependencies\User;

class TestHasPermission extends TestCase
{
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
