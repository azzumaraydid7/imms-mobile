<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards screens that need a live API token.
 *
 * This is not Laravel's `auth` guard — the app has no local users table, so the
 * presence of a session token is the whole check.
 */
class RequireApiSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('imms.token')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
