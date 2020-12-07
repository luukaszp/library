<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->workers->is_admin === true) {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->is_admin === true) {
            return $next($request);
        }

        return response()->json(
            [
            'success' => false,
            'message' => 'You do not have Administrator permissions.'
            ], 401
        );
    }
}
