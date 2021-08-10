<?php

namespace Iya30n\DynamicAcl\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->hasPermission('fullAccess')) return $next($request);

        foreach (request()->route()->parameters as $param) {
            if ($param->user_id && $param->user_id !== auth()->id())
                return back();
        }

        return $next($request);
    }
}
