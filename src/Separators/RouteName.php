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

    public function checkAccess($access, $permissions): bool
    {
        $userPermissions = (strpos($access, '.') != false) ?
            \Arr::dot($permissions) :
            $permissions;

        return isset($userPermissions[$access]) || isset($userPermissions['fullAccess']);
    }
}