<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class RedirectIfNotOwner
{
    public function handle(Request $request, Closure $next): Response|Null
    {
        if (auth()->check()) {

            setPermissionsTeamId(auth()->user()->tenant_id);

            if (! auth()->user()->isOwner()) {
                return redirect(route('dashboard'));
            }
        }

        return $next($request);
    }
}
