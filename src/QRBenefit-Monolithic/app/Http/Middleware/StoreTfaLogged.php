<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;
use App\Http\ResponseData;

class StoreTfaLogged
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
		if ( $user->tfa_enabled == 1 and  $user->store->tfaEnabled() == 1) {
			$tfa_token = $request->header('tfa');
			if ($user->tfa_token != $tfa_token) {
				$this->responseData = new ResponseData();
				$errors = array(
					"tfa" => ["Unauthenticated"],
				);
				return response()->json($this->responseData->error($errors), 401);
			}
		}
		return $next($request);
	}
}
