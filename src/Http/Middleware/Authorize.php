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

        $foreignKey = $foreignKey ? trim($foreignKey) : $user->getForeignKey();

        if ($user->hasPermission('fullAccess'))
            return $next($request);

        foreach (request()->route()->parameters as $key => $param) {
            if (! $param instanceof Model) {
                $modelNamespace = "\\App\\Models\\" . ucfirst($key);

                if (! class_exists($modelNamespace)) continue;

                $param = app($modelNamespace)->findOrFail($param);
            }

            $relationId = $param->getOriginal($foreignKey);

            throw_if( ! $relationId, new \Exception("The foreign key \"$foreignKey\" does not exists on " . get_class($param)));

            if ($relationId !== $user->getOriginal($user->getKeyName())) {
                if (url()->previous() == url()->current())
                    return redirect('/');

                return back();
            }
        }

        return $next($request);
    }
}
