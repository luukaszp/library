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
        if (Auth::check() && \App\Worker::find(auth()->user()->id)) {
            return $next($request);
        }

        return response()->json(
            [
            'success' => false,
            'message' => Auth::user()->workers
            ], 401
        );
    }
}
