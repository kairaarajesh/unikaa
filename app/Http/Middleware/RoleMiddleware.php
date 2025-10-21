<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || (! $user->hasRole('admin') && $user->hasRole('1'))) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

