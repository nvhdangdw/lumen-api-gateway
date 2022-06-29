<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Http\ResponseData;
use Auth;

class SupplierController extends Controller
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
		foreach ($user->groupUser->userPermissions as $user_permission) {
			$permissions[] = array(
				"key" => $user_permission->permission->key,
				"name" => $user_permission->permission->name
			);
		}
		$supplier = $user->supplier;
		$logo = "";
		if (!empty($supplier->logo)){
			$logo = "/storage/".$supplier->logo;
		}
		$data = array(
			"email" => $user->email,
			"name" => $user->name,
			"supplier" => array(
				"supplier_id" => $supplier ? $supplier->supplier_id : "",
				"logo" => $logo,
				"name" => $supplier ? $supplier->name : "",
			),
			"permissions" => $permissions
		);
		return $this->responseData->success($data);
	}
}
