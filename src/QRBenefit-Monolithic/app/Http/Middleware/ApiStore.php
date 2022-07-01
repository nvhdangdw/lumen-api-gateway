<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;

class ApiStore
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
		$value = $request->header('jwt');
		if (empty($value))
			return abort(401);
		$user = User::where('api_token', $value)->first();
		if ($user) {
			Auth::login($user);
			return $next($request);
		}
		return abort(401);
	}
}
