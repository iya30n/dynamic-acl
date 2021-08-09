<?php

namespace Iya30n\DynamicAcl\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authorize
{
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        foreach (static::getMethodParamsFrom(request()) as $param) {
            dump($param);
        }

        die();
    }

    private static function getMethodParamsFrom($request)
    {
        if (array_key_exists('controller', $request->route()->getAction())) {
            $action = $request->route()->getAction()['controller'];
            $method = \Str::after($action, '@');
            $controller = \Str::replaceLast("@$method", '', $action);

            $reflected = new \ReflectionMethod($controller, $method);

            foreach ($reflected->getParameters() as $param) {
                dd($param);
                yield $param->getType()->getName();
            }
        }

        if (array_key_exists('uses', $request->route()->getAction())) {

        }
    }
}
