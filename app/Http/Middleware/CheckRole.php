<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->attributes->get('auth_user');

        if (!$user || !$user->hasRole($role)) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}