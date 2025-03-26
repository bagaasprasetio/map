<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\Pangkalan;
use Illuminate\Support\Facades\Auth;


class CheckPangkalan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $checkPangkalan = Pangkalan::where('user_id', Auth::user()->id);
        
        return $next($request);
    }
}
