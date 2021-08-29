<?php

namespace Iya30n\DynamicAcl\Separators;

use Illuminate\Support\Manager;
use Iya30n\DynamicAcl\Separators\{ControllerMethod, RouteName, Uri};

class Separator extends Manager
{
    public function getDefaultDriver()
    {
        return config('dynamicACL.separator_driver');
    }

    protected function createNameDriver()
    {
        return resolve(RouteName::class);
    }

    protected function createMethodDriver()
    {
        return resolve(ControllerMethod::class);
    }

    protected function createUriDriver()
    {
        return resolve(Uri::class);
    }
}