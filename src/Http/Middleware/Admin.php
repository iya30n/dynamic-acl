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
        if ($this->auth->check()) {
            // TODO: change it to handle with uri and controller
            $routeName = $request->route()->getName();

            if ($this->auth->user()->hasPermission($routeName))
                return $next($request);

            // flash()->warning('', 'شما دسترسی به این بخش را ندارید.');
            // TODO: if back() is empty or user is not login anymore, redirect to login page;
            return back();
        }

        return redirect('/login');
    }
}
