<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
//        if (Auth::guard($guard)->check()) {
//            return redirect(RouteServiceProvider::HOME);
//        }
        if (Auth::guard($guard)->check()) {
            //return redirect(RouteServiceProvider::HOME);
            $role = Auth::user()->role_id;
            switch ($role) {
                case '1':
                    return redirect('admin/dashboard');
                    break;
                case '2':
                    return redirect('seller/dashboard');
                    break;
                case '3':
                    return redirect('buyer/dashboard');
                    break;
                default:
                    return redirect('/home');
                    break;
            }
        }

        return $next($request);
    }
}
