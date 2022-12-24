<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class Customer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('customer')->check()) {
            return $next($request);
        } else {
            if ($request->expectsJson()) {
                return response()->json(Lang::get('messages.unauthenticated'), Response::HTTP_UNAUTHORIZED);
            }
            return redirect()->route('login.form');
        }
    }
}
