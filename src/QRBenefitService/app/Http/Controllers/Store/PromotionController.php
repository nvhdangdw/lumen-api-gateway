<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Store\HomeController;
use App\Promotion;
use App\Supplier;
use App\User;

class PromotionController extends HomeController
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
        protected $data;
        protected $user;
        protected $supplier;
    
	public function __construct()
	{
            $this->middleware(function ($request, $next) {
                $this->user = Auth::user();
                $this->data['supplier'] = Supplier::where('user_id','=',$this->user->id)->first();
                return $next($request);
            });
	}

	public function index(){
		$promotions = Promotion::where('supplier_id','=',$this->data['supplier']->supplier_id)->get();
//                dd($promotions);
                $this->data['promotion_data'] = $promotions;
		return view('store.promotion.index',$this->data);
	}

//	public function showViewInfo($id){
//		$order = Order::find($id);
//		return view('admin.order.info',['order' => $order]);
//	}
}
