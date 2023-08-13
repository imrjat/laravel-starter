<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class RedirectIfNotSubscribed
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (auth()->check()) {

            setPermissionsTeamId(auth()->user()->tenant_id);

            if (! auth()->user()->tenant->isValid()) {
                return redirect(route('dashboard'));
            }
        }

        return $next($request);
    }
}
