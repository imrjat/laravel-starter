<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNotSubscribed
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {

            setPermissionsTeamId(auth()->user()->tenant_id);

            if (! auth()->user()->tenant->valid()) {
                return redirect(route('upgrade'));
            }
        }

        return $next($request);
    }
}
