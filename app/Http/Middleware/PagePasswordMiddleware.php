<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagePasswordMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Check if the provided password matches the user's password
            if (password_verify($request->password, Auth::user()->password)) {
                return $next($request);
            }
        }

        // Redirect or return error response if password doesn't match
        return response()->json(['error' => 'Unauthorized.'], 401);
    }
}
