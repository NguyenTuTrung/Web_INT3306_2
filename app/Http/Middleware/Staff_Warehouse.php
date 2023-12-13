<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Staff_Warehouse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!empty(Auth::user())) {
            if (Auth::user()->user_type == "staff_warehouse") {
                return $next($request);
            } else {
                $notify[] = ['error', 'Manager not allowed to access this page'];
                return redirect()->route('login')->withNotify($notify);
            }
        }else {
            $notify[] = ['error', 'Manager not allowed to access this page'];
            return redirect()->route('login')->withNotify($notify);
        }
    }
}
