<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Discount;
use App\Supplier;
use Auth;
use App\Http\ResponseData;
use Illuminate\Support\Facades\Validator;
use RobThree\Auth\TwoFactorAuth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Cache;

class TwoFactorAuthController extends Controller
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
    public function getSrcQrcode()
    {
        $user = Auth::user();
        $tfa = new TwoFactorAuth(config('tfa.issuer'));
        $secret = $tfa->createSecret();
        Cache::put('secret', $secret, 180);
        $src = "data:image/png;base64,". base64_encode(QrCode::format('png')->size(200)->generate($tfa->getQRText($user->name, Cache::get('secret'))));
        return $this->responseData->success(["secret" => Cache::get('secret'), "src" => $src]);
    }

    public function verifyCode(Request $request)
    {
        $user = Auth::user();
        $errors = array(
            "code" => [__('validation.tfa_incorrect')],
        );
        if (empty($user->tfa_secret)) {
            return $this->responseData->error($errors);
        }
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:6',
        ]);
        // Check validation failure
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->responseData->error($errors);
        }
        $tfa = new TwoFactorAuth();
        $result = $tfa->verifyCode($user->tfa_secret, $request->input("code"));
        if ($result) {
            $tfa_token = Str::random(60);
            $user->tfa_token = $tfa_token;
            $user->save();
            return $this->responseData->success(["tfa_token" => $tfa_token]);
        } else {
            return $this->responseData->error($errors);
        }
    }

    public function enabled(Request $request)
    {
        return $this->updateTfaEnabled($request, 1);
    }

    public function disabled(Request $request)
    {
        return $this->updateTfaEnabled($request, 0);
    }

    protected function updateTfaEnabled($request, $status)
    {
        $user = Auth::user();
        $errors = array(
            "code" => [
                __('validation.tfa_incorrect')
            ],
        );
        if ((empty(Cache::get('secret')) && ($user->tfa_enabled == 0)) || (empty($user->tfa_secret) && ($user->tfa_enabled == 1))) {
            return $this->responseData->error($errors);
        }
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:6',
        ]);
        // Check validation failure
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->responseData->error($errors);
        }
        $secret = ($status == 1) ? Cache::get('secret') : $user->tfa_secret;
        $tfa = new TwoFactorAuth();
        $result = $tfa->verifyCode($secret, $request->input("code"));
        if ($result) {
            $user->tfa_enabled = $status;
            $tfa_token = "";
            if ($status == 1) {
                $tfa_token = Str::random(60);
                $user->tfa_secret = Cache::get('secret');
                $user->tfa_token = $tfa_token;
            }
            $user->save();
            Cache::forget('secret');
            return $this->responseData->success(["tfa_token" => $tfa_token, "tfa_enabled" => $user->tfa_enabled]);
        } else {
            return $this->responseData->error($errors);
        }
    }
}