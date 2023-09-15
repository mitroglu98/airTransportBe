<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$roles)
    {
        \Log::info('Expected roles for the route: ', $roles);
    
        if (!$request->user()) {
            \Log::info('No user authenticated.');
            return response('Unauthorized', 403);
        }
    
        \Log::info('Authenticated user: ', [$request->user()]);
        $userRole = $request->user()->role;  // get role directly from user object
        \Log::info('User role: ', [$userRole]);
    
        if (!in_array($userRole, $roles)) {
            return response('Unauthorized', 403);
        }
    
        return $next($request);
    }
    
    
}
