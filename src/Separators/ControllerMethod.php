<?php


namespace Iya30n\DynamicAcl\Separators;


use Illuminate\Support\Str;

class ControllerMethod extends BaseSeparator
{
    public function getRoutes()
    {
        $controllersBasePath = config('dynamicACL.controllers_path');

        $separatedRoutes = [];

        foreach ($this->routes as $route) {
            $action = Str::after($route['action'], $controllersBasePath);

            $method = Str::afterLast($action, '@');

            $controller = Str::replaceLast("@$method", '', $action);

            $separatedRoutes[$controller][] = $route;
        }

        return $separatedRoutes;
    }
}