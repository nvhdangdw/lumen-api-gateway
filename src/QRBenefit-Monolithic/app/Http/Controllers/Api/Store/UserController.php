<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\ResponseData;
use App\Setting;
use Auth;
use Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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

    public function info()
    {
        $user = Auth::user();
        $permissions = array();
        foreach ($user->groupUser->groupPermissions as $permission) {
            $permissions[] = $permission->permission->key;
        }
        $settings = array();
        $globalSettings = Setting::where('store_id', $user->store->store_id)->get(['key', 'value']);
        foreach ($globalSettings as $globalSetting) {
            $settings[] = array(
                "key" => $globalSetting->key,
                "value" => $globalSetting->value
            );
        }
        $storeSettings = array();
        foreach ($user->store->settings as $setting) {
            if ($setting->key === 'TFA_ENABLED') {
                $store_tfa_enabled = (int) $setting->value;
            }

            $storeSettings[] = array(
                "key" => $setting->key,
                "value" => $setting->value
            );
        }
        $store = $user->store;
        $logo = "";
        if (!empty($store->logo)) {
            $logo = "/storage/" . $store->logo;
        }
        $data = array(
            "email" => $user->email,
            "name" => $user->name,
            "tfa_enabled" => $user->tfa_enabled,
            "avatar" => $user->getAvatarUrlAttribute(),
            "thumb_avatar" => $user->getThumbAvatarUrlAttribute(),
            "store" => array(
                "store_id" => $store ? $store->store_id : "",
                "logo" => $logo,
                "name" => $store ? $store->name : "",
                "settings" => $storeSettings
            ),
            "group_user" => $user->groupUser->name,
            "permissions" => $permissions,
            "settings" => $settings
        );

        // Check TFA_TOKEN if TFA_ENABLED
        if ($user->tfa_enabled && $store_tfa_enabled) {
            $data['tfa_authenticated'] = request()->header('tfa') === $user->tfa_token;
        }

        return $this->responseData->success($data);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            $errors = array(
                "user" => [trans('validation.user_not_exist')],
            );
            return $this->responseData->error($errors);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'avatar' => 'max:255',
            'file_upload' => 'nullable|mimes:jpg,jpeg,png|max:1024' // image
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->responseData->error($errors);
        }

        $user->name = $request->input("name");

        $thumb_prefix = 'thumb-';

        if ($request->hasFile('file_upload')) {
            $file_upload = $request->file_upload;
            $filename = uniqid() . '.' . "jpg";
            $file_upload->storeAs('public/user/avatar', $filename);
            Storage::delete("public/user/avatar/{$user->avatar}");

            //thumbnail
            $thumb_image = Image::make($file_upload);
            $thumb_Name = $thumb_prefix . $filename;
            $thumb_image->resize(200, 200);
            $thumb_image->save(storage_path() . '/app/public/user/avatar/' . $thumb_Name);
            Storage::delete("public/user/avatar/thumb-{$user->avatar}");

            $user->avatar = $filename;
        }

        $user->save();
        $thump_avatar = $user->avatar;
        $user->avatar = $user->getAvatarUrlAttribute();

        if ($request->hasFile('file_upload')) {
            $thump_avatar = asset(Storage::url('user/avatar/' . $thumb_Name));
        } else {
            $thump_avatar = asset(Storage::url('user/avatar/' . $thumb_prefix . $thump_avatar));
        }

        $user->thumb_avatar = $thump_avatar;

        return $this->responseData->success($user);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            $errors = array(
                "user" => [trans('validation.user_not_exist')],
            );
            return $this->responseData->error($errors);
        }

        $rules = [
            'current_password' => [
                'required',
                'min:8',
                'max:32',
                function ($attribute, $value, $fail) {
                    if (!\Hash::check($value, Auth::user()->password)) {
                        return $fail(trans('validation.password_not_match'));
                    }
                }
            ],
            'password' => 'required|min:8|max:32|different:current_password',
            'password_confirmation' => 'required|required_with:password|same:password',

        ];

        $messages = [
            'password_confirmation.same' => trans('validation.password_confirmation'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->responseData->error($errors);
        }

        $user->password = bcrypt($request->input("password"));

        $user->save();
        $user->avatar = $user->getAvatarUrlAttribute();
        return $this->responseData->success($user);
    }
}
