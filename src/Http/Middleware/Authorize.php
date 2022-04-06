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
    public function handle($request, Closure $next, $foreignKey = "user_id")
    {
        $foreignKey = trim($foreignKey);

        if (auth()->user()->hasPermission('fullAccess')) return $next($request);

        foreach (request()->route()->parameters as $param) {
            $relationId = $param->getOriginal($foreignKey);

            if ($relationId == null && $className = get_class($param))
                throw new \Exception("The foreign key \"$foreignKey\" does not exists on $className");

            if ($relationId !== auth()->id()) {
                if (url()->previous() == url()->current())
                    return redirect('/');

                return back();
            }
        }

        return $next($request);
    }
}
