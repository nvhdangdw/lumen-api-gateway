<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
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
		if (Auth::user() &&  Auth::user()->group_user_id == 1) {
			return $next($request);
		} else if (Auth::user()) {
			return new response(view('admin.confirm_logout'));
		}

		return redirect('admin/login');
	}
}
