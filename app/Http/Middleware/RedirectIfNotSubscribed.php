<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class RedirectIfNotSubscribed
{
    public function handle(Request $request, Closure $next): Response
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
