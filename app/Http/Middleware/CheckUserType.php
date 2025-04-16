<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    public function handle(Request $request, Closure $next, $userType)
    {
        if (!Auth::check()) {
            return redirect('login');
        }
        
        if (Auth::user()->user_type != $userType) {
            return redirect('home')->with('error', 'You do not have permission to access this page.');
        }
        
        return $next($request);
    }
}