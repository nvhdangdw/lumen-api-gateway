<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Discount;
use App\Store;
use App\Order;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}

	public function index(){
		$total = array(
			"customer" => Customer::count(),
			"discount" => Discount::count(),
			"store" => Store::count(),
			"order" => Order::count()
		);

		return view('admin.dashboard',["total" => $total]);
	}
}
