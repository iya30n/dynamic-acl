<?php

namespace Iya30n\DynamicAcl;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;

class Route
{
    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Create a new route command instance.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function __construct()
    {
        $this->router = resolve(Router::class);
    }

    /**
     * Execute the console command.
     *
     * @return Collection
     */
    public function getUserDefined(): Collection
    {
        $routes = $this->router->getRoutes();

        $routes = collect($routes)->map(function ($route) {
            return [
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => ltrim($route->getActionName(), '\\'),
                'middleware' => $this->getMiddleware($route),
            ];
        });

        return $this->getAclRoutes($routes);
    }

    /**
     * Get the middleware for the route.
     *
     * @param \Illuminate\Routing\Route $route
     * @return string
     */
    protected function getMiddleware($route)
    {
        return collect($this->router->gatherRouteMiddleware($route))->map(function ($middleware) {
            return $middleware instanceof Closure ? 'Closure' : $middleware;
        })->implode("\n");
    }

    /**
     * Get only routes contains DynamicAcl middleware
     *
     * @return Collection
     */
    protected function getAclRoutes($routes): Collection
    {
        return $routes->filter(function ($route) {
            // $controllerBasePath = config('dynamicACL.controllers_path');

            $isDynamical = Str::contains($route['middleware'], 'DynamicAcl');

            $inIgnoreList = in_array($route['name'], config('dynamicACL.ignore_list', []));

            // if (Str::contains($route['action'], $controllerBasePath) && $isDynamical && !$inIgnoreList)
            if ($isDynamical && !$inIgnoreList)
                return $route;
        });
    }
}
