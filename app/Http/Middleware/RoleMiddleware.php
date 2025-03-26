<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role)
    {

        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        // Cek apakah user punya role yang diizinkan
        if (!in_array(Auth::user()->role, $role)) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
