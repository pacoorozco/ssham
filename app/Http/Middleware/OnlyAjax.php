<?php

namespace App\Http\Middleware;

use Closure;

class OnlyAjax
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->ajax()) {
            return response(view('errors.403'), 403);
        }

        return $next($request);
    }
}
