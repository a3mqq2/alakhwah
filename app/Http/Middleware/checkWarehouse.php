<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkWarehouse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(in_array($request->warehouse,auth()->user()->warehouses()->get()->pluck('id')->toArray())) {
            return $next($request);
        } else {
            return redirect('/home')->withErrors(['ليس لديك الصلاحيه للدخول الى الصفحه']);
        }
    }
}
