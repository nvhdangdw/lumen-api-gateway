<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\GroupUser;
use App\Store;
use Illuminate\Support\Str;

class UserController extends Controller
{
	protected $perPage = 5;
	protected $user_group_id = 1;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}

	public function index(Request $request){
		$query = User::query();
		$query->join('group_user', 'users.group_user_id', '=', 'group_user.group_user_id');
		$query->select('users.*');
		$order_fields = ["id","name","email","group_user"];
		$order_parameters = array();
		$filter_parameters = array();
		if (isset($request->id) && !empty($request->id)) {
			$query->where('users.id', 'LIKE', '%' . $request->id . '%');
			$filter_parameters["id"] = $request->id;
		}
		if (isset($request->name) && !empty($request->name)) {
			$query->where('users.name', 'LIKE', '%' . $request->name . '%');
			$filter_parameters["name"] = $request->name;
		}
		if (isset($request->email) && !empty($request->email)) {
			$query->where('users.email', 'LIKE', '%' . $request->email . '%');
			$filter_parameters["email"] = $request->email;
		}
		if (isset($request->sort) && !empty($request->sort) && in_array($request->sort, $order_fields)) {
			$order = "asc";
			if (isset($request->order) && !empty($request->order) && strtolower($request->order) == "desc") {
				$order = "desc";
			}
			$order_parameters['sort'] = $request->sort;
			$order_parameters['order'] = $order;
			if ($request->sort == 'group_user'){
				$query->orderBy("group_user.name",$order);
			} else {
				$query->orderBy("users.".$request->sort,$order);
			}
		}
		if (empty($order_parameters))
			$query->orderBy('users.created_at','decs');
		$users = $query->paginate($this->perPage);
		// add filter to link pagination
		$users->appends(array_merge($filter_parameters, $order_parameters))->links();
		return view('admin.user.index',['users' => $users, 'filter_parameters' => $filter_parameters, "order_parameters" => $order_parameters]);
	}

	public function showViewAdd(){
		$group_users = GroupUser::all();
		$stores = Store::all();
		return view('admin.user.add',["group_users"=>$group_users, "stores"=>$stores]);
	}

	public function create(Request $request){
		$validatedData = $request->validate([
			'name' => 'required|string|max:60',
			'email' => 'required|unique:users|email|max:50',
			'password' => 'required|string|min:6|confirmed',
			'store_id' => 'required|numeric',
			'group_user_id' => 'required|numeric',
		]);

		$user = new User;
		$user->name = $request->input("name");
		$user->email = $request->input("email");
		$user->password = Hash::make($request->input("password"));
		$user->group_user_id = $request->input("group_user_id");
		$user->store_id = $request->input("store_id");
		$user->save();
		return redirect('/admin/user');
	}

	public function showViewEdit($id){
		$user = User::find($id);
		$group_users = GroupUser::all();
		$stores = Store::all();
		return view('admin.user.edit',['user' => $user,"group_users"=>$group_users, "stores"=>$stores]);
	}

	public function update($id, Request $request){
		$user = User::find($id);
		if (!$user)
			return redirect()->route('admin_user_show_view_edit',['id' => $id]);
		$validatedData = $request->validate([
			'name' => 'required|string|max:60',
			'email' => 'required|unique:users,email,'.$user->id.',id|email|max:50',
			'password' => 'nullable|min:6|string|confirmed',
			'store_id' => 'required|numeric',
			'group_user_id' => 'required|numeric',
		]);
		$user->name = $request->input("name");
		$user->email = $request->input("email");
		if(!empty($request->input("password")))
			$user->password = Hash::make($request->input("password"));
		$user->group_user_id = $request->input("group_user_id");
		$user->store_id = $request->input("store_id");
		$user->save();
		return redirect('/admin/user');
	}

	public function delete(Request $request){
		$message = '';
		if ($request->isMethod('post')) {
			$ids = $request->input("selected");
			if(!empty($ids)){
				User::destroy($ids);
				$message = 'Delete successful';
			}
		}
		$query = User::query();
		$query->join('group_user', 'users.group_user_id', '=', 'group_user.group_user_id');
		$query->select('users.*');
		$order_fields = ["id","name","email","group_user"];
		$order_parameters = array();
		$filter_parameters = array();
		if (isset($request->id) && !empty($request->id)) {
			$query->where('users.id', 'LIKE', '%' . $request->id . '%');
			$filter_parameters["id"] = $request->id;
		}
		if (isset($request->name) && !empty($request->name)) {
			$query->where('users.name', 'LIKE', '%' . $request->name . '%');
			$filter_parameters["name"] = $request->name;
		}
		if (isset($request->email) && !empty($request->email)) {
			$query->where('users.email', 'LIKE', '%' . $request->email . '%');
			$filter_parameters["email"] = $request->email;
		}
		if (isset($request->sort) && !empty($request->sort) && in_array($request->sort, $order_fields)) {
			$order = "asc";
			if (isset($request->order) && !empty($request->order) && strtolower($request->order) == "desc") {
				$order = "desc";
			}
			$order_parameters['sort'] = $request->sort;
			$order_parameters['order'] = $order;
			if ($request->sort == 'group_user'){
				$query->orderBy("group_user.name",$order);
			} else {
				$query->orderBy("users.".$request->sort,$order);
			}
		}
		if (empty($order_parameters))
			$query->orderBy('created_at','decs');
		$users = $query->paginate($this->perPage);
		// add filter to link pagination
		$users->appends(array_merge($filter_parameters, $order_parameters))->links();
		return view('admin.user.index',['users' => $users,'filter_parameters' => $filter_parameters,"order_parameters" => $order_parameters, 'message' => $message]);
	}

	public function showViewInfo($id){
		$user = User::find($id);
		return view('admin.user.info',['user' => $user]);
	}
}
