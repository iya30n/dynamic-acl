<?php

namespace App\DynamicAcl;

use Illuminate\Support\{Collection, Str};

class Separator
{
	/** 
	 * Get routes separated by uri
	 * 
	 * @param Illuminate\Support\Collection $routes
	 * 
	 * @return Array
	 */
	public function byUri(Collection $routes)
	{
		$separetedRoutes = [];

		foreach ($routes as $route) {
			$routeUri = $route['uri'] ?? '';

			$last = Str::afterLast($routeUri, '/');

			$uri = Str::replaceLast("$last", '', $routeUri);

			// $route['uri'] = $last;

			$separetedRoutes[$uri][] = $route;
		}

		return $separetedRoutes;
	}

	/** 
	 * Get routes separated by route name
	 * 
	 * @param Illuminate\Support\Collection $routes
	 * 
	 * @return Array
	 */
	public function byName(Collection $routes)
	{
		$separetedRoutes = [];

		foreach ($routes as $route) {
			$routeName = $route['name'] ?? '';

			$last = Str::afterLast($routeName, '.');

			$name = Str::replaceLast(".$last", '', $routeName);

			$route['name'] = $last;

			$separetedRoutes[$name][] = $route;
		}

		return $separetedRoutes;
	}

	/** 
	 * Get routes of each controller
	 * 
	 * @param Illuminate\Support\Collection
	 * 
	 * @return Array
	 */
	public function byController(Collection $routes)
	{
		$controllersBasePath = config('dynamicACL.controllers_path');

		$separetedRoutes = [];

		foreach ($routes as $route) {
			$action = Str::after($route['action'], $controllersBasePath);

			$method = Str::afterLast($action, '@');

			$controller = Str::replaceLast("@$method", '', $action);

			$separetedRoutes[$controller][] = $route;
		};

		return $separetedRoutes;
	}
}