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
        if (strpos($access, '.*') != false) {
            $access = str_replace(".*", "", $access);
            return array_key_exists($access, $permissions);
        }

        $userPermissions = (strpos($access, '.') != false) ?
                            \Arr::dot($permissions) :
                            $permissions;

        if (isset($userPermissions['fullAccess'])) return true;

        return isset($userPermissions[$access]);
    }
}