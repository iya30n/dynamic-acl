<?php

namespace Tests\Dependencies;

use Illuminate\Database\Eloquent\Model;
use Orchestra\Testbench\Concerns\WithFactories;

class Post extends Model
{
    protected $guarded = ['id'];

	public function user()
	{
		$this->belongsTo(User::class);
	}
}
