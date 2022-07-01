<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;
use App\Http\ResponseData;

class StoreTfaSetting
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
		$user = Auth::user();
		if ( $user->store->tfaEnabled() != 1) {
			$this->responseData = new ResponseData();
			$errors = array(
				"tfa" => ["Disabled"],
			);
			return response()->json($this->responseData->error($errors), 401);
		}
		return $next($request);
	}
}
