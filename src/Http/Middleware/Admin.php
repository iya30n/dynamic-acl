<?php

namespace Iya30n\DynamicAcl\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Admin
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
        if ($this->auth->guest())
            return redirect('/login');

        $routeName = $request->route()->getName();

        if ($this->auth->user()->hasPermission($routeName))
            return $next($request);

        // flash()->warning('', 'شما دسترسی به این بخش را ندارید.');
        if (url()->previous() == url()->current())
            return redirect('/');

        return back();
    }
}
