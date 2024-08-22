<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle( Request $request, Closure $next, $rule): Response
    {
        if(auth()->user()->$rule) {
            return $next($request);
        } else {
            return redirect()->back()->withErrors(['ليس لديك الصلاحيه للدخول الى هذه الصفحه']);
        }
    }
}
