<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // if(!Auth::check()){
        //     return response()->json([
        //         'status' => false,
        //         'msg'    => 'Not Allowed'
        //     ], 403);
        // }

        $user = Auth::user();
        if(!in_array($user->role, $roles)){
            return response()->json([
                'status' => false,
                'msg'    => 'Invalid Access'
            ], 403);
        }
        return $next($request);
    }
}
