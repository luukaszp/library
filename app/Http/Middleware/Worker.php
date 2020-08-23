<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Worker
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
        if (Auth::check() && Auth::user()->is_worker === true) {
            return $next($request);
        }

        return response()->json(
            [
            'success' => false,
            'message' => 'You do not have Worker permissions.'
            ], 403
        );
    }
}
