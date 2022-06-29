<?php
namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\Supplier;
use App\User;
use App\Promotion;
use App\Customer;

/**
 * Description of HomeController
 *
 * @author nvhda
 */
class HomeController extends Controller
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

    public function index()
    {   
        return view('store.dashboard', $this->data);
    }

    public function checkout($customer_id)
    {
        $customer = Customer::find($customer_id);
        $this->data['customer'] = $customer;
        
        $promotions = Promotion::where('supplier_id', '=', $this->data['supplier']->supplier_id)->get();
        $this->data['promotion_data'] = $promotions;

        return view('store.checkout', $this->data);
    }
    
    public function customerInfo($customer_id){
        
        $customer = Customer::find($customer_id);
        $this->data['customer'] = $customer;
        
        if($customer){
                $qr_customer['customer_id'] = $customer->customer_id;
                $qr_customer['first_name'] = $customer->first_name;
                $qr_customer['last_name'] = $customer->last_name;
                $qr_customer['email'] = $customer->email;
                $qr_customer['phone_number'] = $customer->phone_number;
        }
        $qr_string = json_encode($qr_customer);
        
        $this->data['qr_string'] = $qr_string;
        
        return view('store.customer', $this->data);
    }
}
