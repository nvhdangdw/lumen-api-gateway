<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Http\ResponseData;

class CheckPermission
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next,$key)
	{
		$user = Auth::user();
		$permissions = array();
		foreach ($user->groupUser->groupPermissions as $permission) {
			$permissions[] = $permission->permission->key;
		}
		$this->responseData = new ResponseData();
		if (!in_array($key, $permissions)) {
			$errors = array(
				"route" => ["Permission denied"],
			);
			return response()->json($this->responseData->error($errors), 403);
		}
		return $next($request);
	}
}
