<?php

namespace Iya30n\DynamicAcl;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;

class Route
{
	/** 
	 * Get all routes
	 * 
	 * @return Illuminate\Support\Collection
	 */
	private function getCollected(): Collection
	{
		Artisan::call('route:list --json');

		return collect(
			json_decode(Artisan::output(), true)
		);
	}

	/** 
	 * Get developer defined routes (filter by controllers namespace)
	 * 
	 * @return Illuminate\Support\Collection
	 */
	public function getUserDefined(): Collection
	{
		return $this->getCollected()->filter(function ($route) {
			$controllerBasePath = config('dynamicACL.controllers_path');

			if (\Str::contains($route['action'], $controllerBasePath))
				return $route;
		});
	}
}
