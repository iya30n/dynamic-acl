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
    public function handle($request, Closure $next, $foreignKey = "user_id")
    {
        $foreignKey = trim($foreignKey);

        if (auth()->user()->hasPermission('fullAccess')) return $next($request);

        foreach (request()->route()->parameters as $key => $param) {
            if (! $param instanceof Model) {
                $modelNamespace = "\\App\\Models\\" . ucfirst($key);

                if (! class_exists($modelNamespace)) continue;

                $param = app($modelNamespace)->findOrFail($param);
            }

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
