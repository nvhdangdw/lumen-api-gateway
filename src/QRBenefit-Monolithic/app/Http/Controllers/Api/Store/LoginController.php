<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Http\ResponseData;
use Auth;

class LoginController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->responseData = new ResponseData();
	}

	public function index(Request $request){
		$validator = Validator::make($request->all(), [
			'email' => 'required|string',
			'password' => 'required|string',
		]);
		// Check validation failure
		if ($validator->fails()) {
			$errors = $validator->errors();
			return $this->responseData->error($errors);
		}
		$credentials = $request->only('email', 'password');

		if ($token = auth("api")->attempt($credentials)) {
			$data = array(
				"token" => $token
			);
			return $this->responseData->success($data);
		} else {
			$errors = array(
				"email" => [trans('validation.login_incorrect')],
			);
			return $this->responseData->error($errors);
		}
	}
}
