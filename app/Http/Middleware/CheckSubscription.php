<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($user->role !== 'ap'){
            return $next($request);
        }
        
        $getSubs = Subscription::where('user_id', $user->id)
                ->whereDate('subs_end', '>=', now())
                ->orderBy('created_at', 'desc')
                ->exists();

        if (!$getSubs){
            return redirect()->route('subs.expired');
        }

        return $next($request);
        
    }
}
