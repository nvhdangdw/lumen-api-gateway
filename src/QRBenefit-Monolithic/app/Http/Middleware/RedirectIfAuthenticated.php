<?php

namespace App\Http\Middleware;

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
		if (Auth::user() &&  Auth::user()->group_user_id == 1) {
			return redirect('/admin');
		}
		if (Auth::user() &&  Auth::user()->group_user_id == 2) {
			return redirect('/store');
		}
		if (Auth::guard($guard)->check()) {
			return redirect('/home');
		}

		return $next($request);
	}
}
