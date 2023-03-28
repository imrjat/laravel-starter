<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNotOwner
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
        if (auth()->check()) {
            if (!auth()->user()->isOwner()) {
                return redirect(route('dashboard'));
            }
        }

        return $next($request);
    }
}
