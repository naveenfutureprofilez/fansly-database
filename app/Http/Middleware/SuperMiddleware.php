<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::check())
        {
            // echo '<pre>';
            // print_r(Auth::user());
            // exit;
            abort(403, 'Invalid Access');
        }
        $user = Auth::user();
        // echo '<pre>';
        // print_r($user);
        // exit;
        if($user->isAdmin !== 'Yes')
        {
            abort(403, 'Invalid Access');
        }
        return $next($request);
    }
}
