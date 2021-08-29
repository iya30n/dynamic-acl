<?php


namespace Iya30n\DynamicAcl\Separators;


use Illuminate\Support\Str;

class RouteName extends BaseSeparator
{
    public function getRoutes()
    {
        $separatedRoutes = [];

        foreach ($this->routes as $route) {
            $routeName = $route['name'] ?? '';

            $last = Str::afterLast($routeName, '.');

            $name = Str::replaceLast(".$last", '', $routeName);

            $route['name'] = $last;

            $separatedRoutes[$name][] = $route;
        }

        return $separatedRoutes;
    }
}