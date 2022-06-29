<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsLoggedInStore
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (Auth::user() &&  Auth::user()->group_user_id == 2) {
//                        dd(Auth::user());
			return $next($request);
		} else if (Auth::user()) {
			abort(404);
		}

		return redirect('store/login');
	}
}
