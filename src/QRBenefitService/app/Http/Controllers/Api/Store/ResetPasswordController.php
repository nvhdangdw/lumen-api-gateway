<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\ResponseData;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\AccountRecovery;
use App\User;

class ResetPasswordController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
		$this->responseData = new ResponseData();
	}

	use ResetsPasswords;
	protected $accountRecovery;

	protected function credentials(Request $request)
	{
		return $request->only(
			'password', 'password_confirmation', 'token'
		);
	}

	public function reset(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'token' => 'required',
			'password' => 'required|confirmed|min:8',
		]);
		// Check validation failure
		if ($validator->fails()) {
			$errors = $validator->errors();
			return $this->responseData->error($errors);
		}
		$this->accountRecovery = AccountRecovery::where('status', 0)
			->where('token',$request->token )->first();
		$email = "";
		if ( $this->accountRecovery ) {
			$email = $this->accountRecovery->email;
		} else {
			return $this->responseData->error(['email' => [trans("passwords.token")]]);
		}
		$data = array(
			"password" => $request->password,
			"password_confirmation" => $request->password_confirmation,
			"token" => $request->token,
			"email" => $email
		);

		// Here we will attempt to reset the user's password. If it is successful we
		// will update the password on an actual user model and persist it to the
		// database. Otherwise we will parse the error and return the response.
		$response = $this->broker()->reset(
			$data, function ($user, $password) {
				$this->resetPassword($user, $password);
			}
		);

		// If the password was successfully reset, we will redirect the user back to
		// the application's home authenticated view. If there is an error we can
		// redirect them back to where they came from with their error message.
		return $response == Password::PASSWORD_RESET
					? $this->sendResetResponse($request, $response)
					: $this->sendResetFailedResponse($request, $response);
	}


	protected function sendResetResponse(Request $request, $response)
	{
		$this->accountRecovery->status = 1;
		$this->accountRecovery->save();
		return $this->responseData->success(['email' => [trans($response)]]);
	}

	protected function sendResetFailedResponse(Request $request, $response)
	{
		return $this->responseData->error(['email' => [trans($response)]]);
	}
}
