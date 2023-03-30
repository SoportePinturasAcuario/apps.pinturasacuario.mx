<?php

namespace Apps\Http\Middleware\Rols;
use Illuminate\Support\Facades\Auth;

use Closure;

class OnlyCustomers
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
        if (empty(Auth::user()->roles->firstWhere('id', 6))) {
            return response()->json([
                'message' => 'Sorry, you are not authorized to access this resource.',
                'errors' => [
                    ['permissions' => ['insufficients.']]
                ]
            ], 401);
        }

        return $next($request);
    }
}
