<?php

namespace Iya30n\DynamicAcl;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;

class Route
{
    /**
     * Get all routes
     *
     * @return Collection
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
     * @return Collection
     */
	public function getUserDefined(): Collection
	{
		return $this->getCollected()->filter(function ($route) {
			$controllerBasePath = config('dynamicACL.controllers_path');

			$isDynamical = Str::contains($route['middleware'], 'DynamicAcl');

			if (Str::contains($route['action'], $controllerBasePath) && $isDynamical)
				return $route;
		});
	}
}
