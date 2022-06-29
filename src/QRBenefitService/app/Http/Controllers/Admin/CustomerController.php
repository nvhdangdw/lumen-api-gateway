<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;

class CustomerController extends Controller
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
		$query = Customer::query();
		$order_fields = ["id", "first_name", "last_name", "email", "telephone"];
		$order_parameters = array();
		$filter_parameters = array();
		if (isset($request->id) && !empty($request->id)) {
			$query->where('customer_id', 'LIKE', '%' . $request->id . '%');
			$filter_parameters["id"] = $request->id;
		}
		if (isset($request->first_name) && !empty($request->first_name)) {
			$query->where('firstname', 'LIKE', '%' . $request->first_name . '%');
			$filter_parameters["first_name"] = $request->first_name;
		}
		if (isset($request->last_name) && !empty($request->last_name)) {
			$query->where('lastname', 'LIKE', '%' . $request->last_name . '%');
			$filter_parameters["last_name"] = $request->last_name;
		}
		if (isset($request->email) && !empty($request->email)) {
			$query->where('email', 'LIKE', '%' . $request->email . '%');
			$filter_parameters["email"] = $request->email;
		}
		if (isset($request->telephone) && !empty($request->telephone)) {
			$query->where('telephone', 'LIKE', '%' . $request->telephone . '%' );
			$filter_parameters["telephone"] = $request->telephone;
		}
		if (isset($request->sort) && !empty($request->sort) && in_array($request->sort, $order_fields)) {
			$order = "asc";
			if (isset($request->order) && !empty($request->order) && strtolower($request->order) == "desc") {
				$order = "desc";
			}
			$order_parameters['sort'] = $request->sort;
			$order_parameters['order'] = $order;
			if ($request->sort == 'id'){
				$query->orderBy("customer_id",$order);
			} else {
				$query->orderBy($request->sort,$order);
			}
		}
		if (empty($order_parameters))
			$query->orderBy('created_at','decs');
		$customers = $query->paginate($this->perPage);
		// add filter to link pagination
		$customers->appends(array_merge($filter_parameters, $order_parameters))->links();
		return view('admin.customer.index',['customers' => $customers, 'filter_parameters' => $filter_parameters, "order_parameters" => $order_parameters]);
	}

	public function showViewAdd(){
		return view('admin.customer.add');
	}

	public function create(Request $request){
		$validatedData = $request->validate([
			'first_name' => 'required|string|max:30',
			'last_name' => 'required|string|max:30',
			'email' => 'required|unique:customer|email|max:50',
			'telephone' => 'required|regex:/^[+]*[0-9]{10,11}$/i|string|max:12',
		]);
		$customer = new Customer;

		$customer->firstname = $request->input("first_name");
		$customer->lastname = $request->input("last_name");
		$customer->email = $request->input("email");
		$customer->telephone = $request->input("telephone");

		$customer->save();
		return redirect('/admin/customer');
	}

	public function showViewEdit($id){
		$customer = Customer::find($id);
		return view('admin.customer.edit',['customer' => $customer]);
	}

	public function update($id, Request $request){
		$customer = Customer::find($id);
		if (!$customer)
			return redirect()->route('admin_customer_show_view_edit',['id' => $id]);
		$validatedData = $request->validate([
			'first_name' => 'required|string|max:30',
			'last_name' => 'required|string|max:30',
			'email' => 'required|unique:customer,email,'.$customer->customer_id.',customer_id|email|max:50',
			'telephone' => 'required|regex:/^[+]*[0-9]{10,11}$/i|string|max:12',
		]);
		$customer->firstname = $request->input("first_name");
		$customer->lastname = $request->input("last_name");
		$customer->email = $request->input("email");
		$customer->telephone = $request->input("telephone");

		$customer->save();
		return redirect('/admin/customer');
	}

	public function delete(Request $request){
		$message = '';
		if ($request->isMethod('post')) {
			$ids = $request->input("selected");
			if(!empty($ids)){
				Customer::destroy($ids);
				$message = 'Delete successful';
			}
		}
		$query = Customer::query();
		$order_fields = ["id", "first_name", "last_name", "email", "telephone"];
		$order_parameters = array();
		$filter_parameters = array();
		if (isset($request->id) && !empty($request->id)) {
			$query->where('customer_id', 'LIKE', '%' . $request->id . '%');
			$filter_parameters["id"] = $request->id;
		}
		if (isset($request->first_name) && !empty($request->first_name)) {
			$query->where('firstname', 'LIKE', '%' . $request->first_name . '%');
			$filter_parameters["first_name"] = $request->first_name;
		}
		if (isset($request->last_name) && !empty($request->last_name)) {
			$query->where('lastname', 'LIKE', '%' . $request->last_name . '%');
			$filter_parameters["last_name"] = $request->last_name;
		}
		if (isset($request->email) && !empty($request->email)) {
			$query->where('email', 'LIKE', '%' . $request->email . '%');
			$filter_parameters["email"] = $request->email;
		}
		if (isset($request->telephone) && !empty($request->telephone)) {
			$query->where('telephone', 'LIKE', '%' . $request->telephone . '%' );
			$filter_parameters["telephone"] = $request->telephone;
		}
		if (isset($request->sort) && !empty($request->sort) && in_array($request->sort, $order_fields)) {
			$order = "asc";
			if (isset($request->order) && !empty($request->order) && strtolower($request->order) == "desc") {
				$order = "desc";
			}
			$order_parameters['sort'] = $request->sort;
			$order_parameters['order'] = $order;
			if ($request->sort == 'id'){
				$query->orderBy("customer_id",$order);
			} else {
				$query->orderBy($request->sort,$order);
			}
		}
		if (empty($order_parameters))
			$query->orderBy('created_at','decs');
		$customers = $query->paginate($this->perPage);
		// add filter to link pagination
		$customers->appends(array_merge($filter_parameters, $order_parameters))->links();
		return view('admin.customer.index',['customers' => $customers, 'filter_parameters' => $filter_parameters, "order_parameters" => $order_parameters, 'message' => $message]);
	}

	public function showViewInfo($id){
		$customer = Customer::find($id);
		$qrCustomer = array();
		if($customer){
			$qrCustomer['customer_id'] = $customer->customer_id;
			$qrCustomer['firstname'] = $customer->first_name;
			$qrCustomer['lastname'] = $customer->last_name;
			$qrCustomer['email'] = $customer->email;
			$qrCustomer['telephone'] = $customer->telephone;
		}
		$qrString = json_encode($qrCustomer);
		return view('admin.customer.info',['customer' => $customer, 'qrString' => $qrString]);
	}
        
        public function checkCustomer(Request $request) {
            $data = $request->all();
            
            $customer_id = $data['customer_data'][0]['customer_id'] ?? 0 ;
            $email = $data['customer_data'][0]['email'] ?? 0 ;
            
            $customer = Customer::where([
                ['customer_id','=',$customer_id],
                ['email','=',$email],
            ])->first();            
            
            $response = array(  
                'customer_data' => json_encode($customer),
                'status' => 'done',
                'msg' => $request->message,
            );
            
            
            if(isset($customer->email)){
                return response()->json($response,200);
            } else {
                return response()->json($response,400);
            }
        }
}
