<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->status === 'pending') {
                Auth::logout();
                return redirect()->route('login')->with('status', 'Your account is pending approval. Please wait for admin approval.');
            }
            
            if ($user->status === 'rejected') {
                Auth::logout();
                $reason = $user->rejection_reason ? ' Reason: ' . $user->rejection_reason : '';
                return redirect()->route('login')->with('status', 'Your account has been rejected.' . $reason);
            }
        }
        
        return $next($request);
    }
}