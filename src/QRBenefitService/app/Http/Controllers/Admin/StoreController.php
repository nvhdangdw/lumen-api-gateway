<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Store;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
	protected $perPage = 5;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}

	public function index(Request $request){
		$query = Store::query();
		$order_fields = ["id","name","email","phone_number"];
		$order_parameters = array();
		$filter_parameters = array();
		if (isset($request->id) && !empty($request->id)) {
			$query->where('store_id', 'LIKE', '%' . $request->id . '%');
			$filter_parameters["id"] = $request->id;
		}
		if (isset($request->name) && !empty($request->name)) {
			$query->where('name', 'LIKE', '%' . $request->name . '%');
			$filter_parameters["name"] = $request->name;
		}
		if (isset($request->email) && !empty($request->email)) {
			$query->where('email', 'LIKE', '%' . $request->email . '%');
			$filter_parameters["email"] = $request->email;
		}
		if (isset($request->phone_number) && !empty($request->phone_number)) {
			$query->where('phone_number', 'LIKE', '%' . $request->phone_number . '%' );
			$filter_parameters["phone_number"] = $request->phone_number;
		}
		if (isset($request->sort) && !empty($request->sort) && in_array($request->sort, $order_fields)) {
			$order = "asc";
			if (isset($request->order) && !empty($request->order) && strtolower($request->order) == "desc") {
				$order = "desc";
			}
			$order_parameters['sort'] = $request->sort;
			$order_parameters['order'] = $order;
			if ($request->sort == 'id'){
				$query->orderBy("store_id",$order);
			} else {
				$query->orderBy($request->sort,$order);
			}
		}
		if (empty($order_parameters))
			$query->orderBy('created_at','decs');
		$stores = $query->paginate($this->perPage);
		// add filter to link pagination
		$stores->appends(array_merge($filter_parameters, $order_parameters))->links();
		return view('admin.store.index',['stores' => $stores, 'filter_parameters' => $filter_parameters, "order_parameters" => $order_parameters]);
	}

	public function showViewAdd(){
		return view('admin.store.add');
	}

	public function create(Request $request){
		$validatedData = $request->validate([
			'name' => 'required|string|max:60',
			'email' => 'required|unique:store|email|max:50',
			'phone_number' => 'required|regex:/^[+]*[0-9]{10,11}$/i|string|max:12',
			'logo' => 'required|image',
			'default_tax' => 'required|numeric',
		]);

		$store = new Store;
		$store->name = $request->input("name");
		$store->phone_number = $request->input("phone_number");
		$store->email = $request->input("email");
		$store->default_tax = $request->input("default_tax");
		$store->save();
		$path = 'store/'.$store->store_id.".".$request->file('logo')->extension();
		$request->file('logo')->storeAs(
			'public', $path
		);
		$store->logo = $path;
		$store->save();
		return redirect('/admin/store');
	}

	public function showViewEdit($id){
		$store = Store::find($id);
		return view('admin.store.edit',['store' => $store]);
	}

	public function update($id, Request $request){
		$store = Store::find($id);
		if (!$store)
			return redirect()->route('admin_store_show_view_edit',['id' => $id]);
		$validatedData = $request->validate([
			'name' => 'required|string|max:60',
			'email' => 'required|unique:store,email,'.$store->store_id.',store_id|email|max:50',
			'phone_number' => 'required|regex:/^[+]*[0-9]{10,11}$/i|string|max:12',
			'logo' => 'nullable|image',
			'default_tax' => 'required|numeric',
		]);
		if (!empty($request->file('logo'))) {
			if (!empty($store->logo) && Storage::exists('public/'.$store->logo)) {
				Storage::delete('public/'.$store->logo);
			}
			$path = 'store/'.$store->store_id.".".$request->file('logo')->extension();
			$request->file('logo')->storeAs(
				'public', $path
			);
			$store->logo = $path;
		}
		$store->name = $request->input("name");
		$store->phone_number = $request->input("phone_number");
		$store->email = $request->input("email");
		$store->default_tax = $request->input("default_tax");
		$store->save();

		return redirect('/admin/store');
	}

	public function delete(Request $request){
		$message = '';
		if ($request->isMethod('post')) {
			$ids = $request->input("selected");
			if(!empty($ids)){
				Store::destroy($ids);
				$message = 'Delete successful';
			}
		}
		$query = Store::query();
		$order_fields = ["id","name","email","phone_number"];
		$order_parameters = array();
		$filter_parameters = array();
		if (isset($request->id) && !empty($request->id)) {
			$query->where('store_id', 'LIKE', '%' . $request->id . '%');
			$filter_parameters["id"] = $request->id;
		}
		if (isset($request->name) && !empty($request->name)) {
			$query->where('name', 'LIKE', '%' . $request->name . '%');
			$filter_parameters["name"] = $request->name;
		}
		if (isset($request->email) && !empty($request->email)) {
			$query->where('email', 'LIKE', '%' . $request->email . '%');
			$filter_parameters["email"] = $request->email;
		}
		if (isset($request->phone_number) && !empty($request->phone_number)) {
			$query->where('phone_number', 'LIKE', '%' . $request->phone_number . '%' );
			$filter_parameters["phone_number"] = $request->phone_number;
		}
		if (isset($request->sort) && !empty($request->sort) && in_array($request->sort, $order_fields)) {
			$order = "asc";
			if (isset($request->order) && !empty($request->order) && strtolower($request->order) == "desc") {
				$order = "desc";
			}
			$order_parameters['sort'] = $request->sort;
			$order_parameters['order'] = $order;
			if ($request->sort == 'id'){
				$query->orderBy("store_id",$order);
			}  else {
				$query->orderBy($request->sort,$order);
			}
		}
		if (empty($order_parameters))
			$query->orderBy('created_at','decs');
		$stores = $query->paginate($this->perPage);
		// add filter to link pagination
		$stores->appends(array_merge($filter_parameters, $order_parameters))->links();
		return view('admin.store.index',['stores' => $stores, 'filter_parameters' => $filter_parameters, "order_parameters" => $order_parameters, 'message' => $message]);
	}

	public function showViewInfo($id){
		$store = Store::find($id);
		return view('admin.store.info',['store' => $store]);
	}
}
