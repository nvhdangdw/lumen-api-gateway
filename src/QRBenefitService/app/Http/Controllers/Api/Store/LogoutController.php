<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Http\ResponseData;
use Auth;

class LogoutController extends Controller
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

	public function index(Request $request)
	{
		auth()->logout();
		return $this->responseData->success([]);
	}
}
