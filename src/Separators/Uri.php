<?php


namespace Iya30n\DynamicAcl\Separators;


use Illuminate\Support\Str;

class Uri extends BaseSeparator
{
    public function getRoutes()
    {
        $separatedRoutes = [];

        foreach ($this->routes as $route) {
            $routeUri = $route['uri'] ?? '';

            $last = Str::afterLast($routeUri, '/');

            $uri = Str::replaceLast("$last", '', $routeUri);

            // $route['uri'] = $last;

            $separatedRoutes[$uri][] = $route;
        }

        return $separatedRoutes;
    }
}