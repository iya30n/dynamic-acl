<?php

namespace Iya30n\DynamicAcl;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RouteList
{
    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * The table headers for the command.
     *
     * @var string[]
     */
    protected $headers = ['Domain', 'Method', 'URI', 'Name', 'Action', 'Middleware'];

    /**
     * The columns to display when using the "compact" flag.
     *
     * @var string[]
     */
    protected $compactColumns = ['method', 'uri', 'action'];

    /**
     * Create a new route command instance.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
//        $this->router->flushMiddlewareGroups();

        if (empty($this->router->getRoutes())) {
            return [];
        }

        if (empty($routes = $this->getRoutes())) {
            return [];
        }

        return $routes;
    }

    /**
     * Compile the routes into a displayable format.
     *
     * @return array
     */
    protected function getRoutes()
    {
        $routes = collect($this->router->getRoutes())->map(function ($route) {
            return [
                'domain' => $route->domain(),
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => ltrim($route->getActionName(), '\\'),
                'middleware' => $this->getMiddleware($route),
            ];
        })->filter()->all();

        return $this->pluckColumns($routes);
    }

    /**
     * Remove unnecessary columns from the routes.
     *
     * @param array $routes
     * @return array
     */
    protected function pluckColumns(array $routes)
    {
        return array_map(function ($route) {
            return Arr::only($route, $this->getColumns());
        }, $routes);
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
     * Get the column names to show (lowercase table headers).
     *
     * @return array
     */
    protected function getColumns()
    {
        $availableColumns = array_map('strtolower', $this->headers);

        return $availableColumns;
    }

}
