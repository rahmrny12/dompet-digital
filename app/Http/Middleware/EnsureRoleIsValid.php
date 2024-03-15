<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRoleIsValid
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
        if (!Auth::check())
            return response()->json(['message' => 'Unauthenticated.'], 401);

        $user = Auth::user();

        foreach ($roles as $role) {
            if ($user->role == $role)
                return $next($request);
        }

        return response()->json(['message' => 'Unauthorized.'], 403);
    }
}
