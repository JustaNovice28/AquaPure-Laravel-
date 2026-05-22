<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class AuthUser
{
    public function handle(Request $request, Closure $next)
    {
        // Check if a user ID is stored in the session
        if (!$userId = session('user_id')) {
            return redirect()->route('admin.login')
                ->with('error', 'Please log in to access the panel.');
        }

        // Fetch the user from the database
        $user = User::find($userId);

        if (!$user) {
            session()->forget('user_id');
            return redirect()->route('admin.login')
                ->with('error', 'User not found.');
        }

        // Make the user available to controllers via the request
        $request->attributes->set('auth_user', $user);

        return $next($request);
    }
}