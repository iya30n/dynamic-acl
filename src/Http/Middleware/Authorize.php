<?php

namespace Iya30n\DynamicAcl\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;

class Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $foreignKey = null)
    {
        $user = auth()->user();

        if ($user->hasPermission('fullAccess'))
            return $next($request);

        foreach ($request->route()->parameters as $key => $param) {
            $param = $param instanceof Model ? $param : [$key => $param];

            if ($user->isOwner($param, $foreignKey)) continue;

            if (url()->previous() == url()->current())
                return redirect('/');

            return back();
        }

        return $next($request);
    }
}
