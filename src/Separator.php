<?php

namespace Iya30n\DynamicAcl;

use Illuminate\Support\{Collection, Str};

class Separator
{
    /**
     * Get routes separated by uri
     *
     * @param Collection $routes
     *
     * @return array
     */
	public function byUri(Collection $routes)
	{
		$separatedRoutes = [];

		foreach ($routes as $route) {
			$routeUri = $route['uri'] ?? '';

			$last = Str::afterLast($routeUri, '/');

			$uri = Str::replaceLast("$last", '', $routeUri);

			// $route['uri'] = $last;

			$separatedRoutes[$uri][] = $route;
		}

		return $separatedRoutes;
	}

    /**
     * Get routes separated by route name
     *
     * @param Collection $routes
     *
     * @return array
     */
	public function byName(Collection $routes)
	{
		$separatedRoutes = [];

		foreach ($routes as $route) {
			$routeName = $route['name'] ?? '';

			$last = Str::afterLast($routeName, '.');

			$name = Str::replaceLast(".$last", '', $routeName);

			$route['name'] = $last;

			$separatedRoutes[$name][] = $route;
		}

		return $separatedRoutes;
	}

    /**
     * Get routes of each controller
     *
     * @param Collection $routes
     * @return array
     */
	public function byController(Collection $routes)
	{
		$controllersBasePath = config('dynamicACL.controllers_path');

		$separatedRoutes = [];

		foreach ($routes as $route) {
			$action = Str::after($route['action'], $controllersBasePath);

			$method = Str::afterLast($action, '@');

			$controller = Str::replaceLast("@$method", '', $action);

			$separatedRoutes[$controller][] = $route;
		};

		return $separatedRoutes;
	}
}