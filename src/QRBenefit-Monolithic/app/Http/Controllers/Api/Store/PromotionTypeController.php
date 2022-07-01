<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PromotionType;
use Auth;
use App\Http\ResponseData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PromotionTypeController extends Controller
{
	protected $perPage = 10;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->responseData = new ResponseData();
	}

	public function getAll()
	{
		$data = PromotionType::all();
		return $this->responseData->success($data);
	}
}
