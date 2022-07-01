<?php

namespace App\Http\Controllers\Api\Store;

use Mail;
use App\Setting;
use App\Customer;
use GuzzleHttp\Client;
use App\Http\ResponseData;
use Illuminate\Http\Request;
use App\Mail\Store\AccountCreated;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Auth;
use Complex\Exception;

class CustomerController extends Controller
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

    public function info($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $result = array(
                "customer_id" => $customer->customer_id,
                "firstname" => $customer->firstname,
                "lastname" => $customer->lastname,
                "telephone" => $customer->telephone,
                "email" => $customer->email,
            );

            return $this->responseData->success($result);
        } else {
            $errors = array(
                "customer" => ["Customer not exist"],
            );
            return $this->responseData->error($errors);
        }
    }

    protected function randString($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

    protected function token($length = 32)
    {
        // Create random token
        $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        $max = strlen($string) - 1;

        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $token .= $string[mt_rand(0, $max)];
        }

        return $token;
    }

    public function create(Request $request)
    {
        $rules = [];

        $rules['firstname'] = 'required|string|max:30';
        $rules['lastname'] = 'required|string|max:30';
        $rules['email'] = 'required|unique:customer|email|max:50';

        $rules['telephone'] = [
            'required',
            'regex:/^([\d +()]{8,32})$/i',
            'string',
            'min:8',
            'max:32'
        ];

        $rules['month_of_birth'] = 'nullable|numeric|min:1|max:12';
        $rules['date_of_birth'] = 'nullable|numeric|min:1|max:31';

        if ($request->input("month_of_birth") && $request->input("date_of_birth")) {
            if ($request->input("month_of_birth") == 2) {
                $rules['date_of_birth'] = 'required|numeric|min:1|max:29';
				$rules['month_of_birth'] = 'required|numeric|min:1|max:12';
            } else if ($request->input("month_of_birth") == 4 || $request->input("month_of_birth") == 6 || $request->input("month_of_birth") == 9 || $request->input("month_of_birth") == 11) {
                $rules['date_of_birth'] = 'required|numeric|min:1|max:30';
				$rules['month_of_birth'] = 'required|numeric|min:1|max:12';
            }
        } else if ($request->input("month_of_birth") || $request->input("date_of_birth")) {
			$rules['date_of_birth'] = 'required|numeric|min:1|max:31';
			$rules['month_of_birth'] = 'required|numeric|min:1|max:12';
		}

        $rules['order_membership_card'] = 'nullable|boolean';

        if ($request->input("order_membership_card")) {
            $order_membership_card = 1;
            $rules['address_line_1'] = 'required|string|max:50';
            $rules['address_line_2'] = 'nullable|string|max:50';
            $rules['state'] = 'required|string|max:50';
            $rules['suburb'] = 'required|string|max:50';
            $rules['postcode'] = 'required|numeric|digits:4';
        } else if (!$request->input("order_membership_card")) {
            $order_membership_card = 0;
            $rules['address_line_1'] = 'nullable|string|max:50';
            $rules['address_line_2'] = 'nullable|string|max:50';
            $rules['state'] = 'nullable|string|max:50';
            $rules['suburb'] = 'nullable|string|max:50';
            $rules['postcode'] = 'nullable|numeric|digits:4';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->responseData->error($errors);
        }

        try {
            $user = Auth::user();
		    $store = $user->store;
            $store_id = $store->store_id;

		    $customer_group = Setting::where('store_id', '=', $store_id)->where('key', '=', 'CUSTOMER_GROUP_ID')->value('value');

            DB::beginTransaction();

            $customer = new Customer;
            $customer->firstname = $request->input("firstname");
            $customer->lastname = $request->input("lastname");
            $customer->email = $request->input("email");
            $customer->telephone = $request->input("telephone");
            $customer->customer_group_id = $customer_group;
            $customer->postcode = $request->input("postcode");
            $customer->date_of_birth = $request->input("date_of_birth");
            $customer->month_of_birth = $request->input("month_of_birth");
            $customer->order_membership_card = $order_membership_card;
            $customer->address_line_1 = $request->input("address_line_1");
            $customer->address_line_2 = $request->input("address_line_2");
            $customer->state = $request->input("state");
            $customer->suburb = $request->input("suburb");
            $customer->save();
            $customer->hashcode = sha1($customer->customer_id);
            $customer->save();

			if ($this->domain_exists($request->input("email"))) {
				$clientBaseUrl = Setting::where('store_id', '=', $store_id)->where('key', Setting::OC_CLIENT_BASE_URL)->value('value');
				$client = new Client(['base_uri' => $clientBaseUrl]);
				$clientId = Setting::where('store_id', '=', $store_id)->where('key', Setting::OC_CLIENT_ID)->value('value');
				$clientSecret = Setting::where('store_id', '=', $store_id)->where('key', Setting::OC_CLIENT_SECRET)->value('value');
				$clientRouteAccount = Setting::where('store_id', '=', $store_id)->where('key', Setting::OC_CLIENT_ROUTE_ACCOUNT)->value('value');
				$salt = $this->token(9);
				$password = $this->randString(9);
				$response = $client->request('POST', $clientRouteAccount, ['form_params' => [
					"client_id" => $clientId,
					"client_secret" => $clientSecret,
					"qr_customer_id" => $customer->customer_id,
					"firstname" => $request->input("firstname"),
					"lastname" => $request->input("lastname"),
					"email" => $request->input("email"),
					"telephone" => $request->input("telephone"),
					"customer_group_id" => $customer_group,
					"salt" => $salt,
					"password" => sha1($salt . sha1($salt . sha1($password))),
					"postcode" => $request->input("postcode"),
					"address_line_1" => $request->input("address_line_1"),
					"address_line_2" => $request->input("address_line_2"),
					"suburb" => $request->input("suburb"),
					"state" => $request->input("state"),
					"order_membership_card" => $order_membership_card
				]]);
				$responseData = json_decode($response->getBody()->getContents(), true);

				$customer->external_id = array_get($responseData, 'oc.customer_id');
				$customer->save();
			} else {
				return $this->responseData->error([["Error: Unable to create account"]]);
			}
            try {
                if (Auth::user()->store->sendEmail()) Mail::send(new AccountCreated($request->input("email"), $password));
            } catch (\Swift_TransportException $e) {
                return $this->responseData->error([["Error: Unable to create account"]]);
            }
            DB::commit();
            return $this->responseData->success($customer);
        } catch (BadResponseException $e) {
            DB::rollBack();
            return $this->responseData->error([
                [
                    array_get(json_decode($e->getResponse()->getBody()->getContents(), true), 'error_message')
                ]
            ]);
        }
    }

	public function domain_exists($email, $record = 'MX'){
		list($user, $domain) = explode('@', $email);
        return checkdnsrr($domain, $record);
	}

	public function update(Request $request)
	{
		$user = Auth::user();
		$store = $user->store;
		$store_id = $store->store_id;

		$customer = Customer::find($request->input("customer_id"));

		if (!$customer) {
			$errors = array(
				"customer" => ["Customer not exist"],
			);
			return $this->responseData->error($errors);
		}

		$rules = [];

		$rules['firstname'] = 'required|string|max:30';
		$rules['lastname'] = 'required|string|max:30';
		$rules['email'] = 'required|unique:customer,email,'.$customer->customer_id.',customer_id|email|max:50';

		$rules['telephone'] = [
			'required',
			'regex:/^([\d +()]{8,32})$/i',
			'string',
			'min:8',
			'max:32'
		];

		$rules['month_of_birth'] = 'nullable|numeric|min:1|max:12';
		$rules['date_of_birth'] = 'nullable|numeric|min:1|max:31';

		if ($request->input("month_of_birth") && $request->input("date_of_birth")) {
			if ($request->input("month_of_birth") == 2) {
				$rules['date_of_birth'] = 'nullable|numeric|min:1|max:29';
			} else if ($request->input("month_of_birth") == 4 || $request->input("month_of_birth") == 6 || $request->input("month_of_birth") == 9 || $request->input("month_of_birth") == 11) {
				$rules['date_of_birth'] = 'nullable|numeric|min:1|max:30';
			}
		}

		$rules['order_membership_card'] = 'nullable|boolean';

		if ($request->input("order_membership_card")) {
			$order_membership_card = 1;
			$rules['address_line_1'] = 'required|string|max:50';
			$rules['address_line_2'] = 'nullable|string|max:50';
			$rules['state'] = 'required|string|max:50';
			$rules['suburb'] = 'required|string|max:50';
			$rules['postcode'] = 'required|numeric|digits:4';
		} else if (!$request->input("order_membership_card")) {
			$order_membership_card = 0;
			$rules['address_line_1'] = 'nullable|string|max:50';
			$rules['address_line_2'] = 'nullable|string|max:50';
			$rules['state'] = 'nullable|string|max:50';
			$rules['suburb'] = 'nullable|string|max:50';
			$rules['postcode'] = 'nullable|numeric|digits:4';
		}

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			$errors = $validator->errors();
			return $this->responseData->error($errors);
		}
		try {
			$customer->firstname = $request->input("firstname");
			$customer->lastname = $request->input("lastname");
			$customer->telephone = $request->input("telephone");
			$customer->postcode = $request->input("postcode");
			$customer->date_of_birth = $request->input("date_of_birth");
			$customer->month_of_birth = $request->input("month_of_birth");
			$customer->order_membership_card = $order_membership_card;
			$customer->address_line_1 = $request->input("address_line_1");
			$customer->address_line_2 = $request->input("address_line_2");
			$customer->state = $request->input("state");
			$customer->suburb = $request->input("suburb");
			$customer->save();
			$customer->name = $customer->firstname . ' ' . $customer->lastname;

			$clientBaseUrl = Setting::where('store_id', '=', $store_id)->where('key', Setting::OC_CLIENT_BASE_URL)->value('value');
			$client = new Client(['base_uri' => $clientBaseUrl]);
			$clientId = Setting::where('store_id', '=', $store_id)->where('key', Setting::OC_CLIENT_ID)->value('value');
			$clientSecret = Setting::where('store_id', '=', $store_id)->where('key', Setting::OC_CLIENT_SECRET)->value('value');
			$clientRouteAccount = Setting::where('store_id', '=', $store_id)->where('key', Setting::OC_CLIENT_ROUTE_ACCOUNT)->value('value');
			$customer_group = Setting::where('store_id', '=', $store_id)->where('key', '=', 'CUSTOMER_GROUP_ID')->value('value');

			$response = $client->request('POST', $clientRouteAccount, ['form_params' => [
				"action" => "update",
				"client_id" => $clientId,
				"client_secret" => $clientSecret,
				"qr_customer_id" => $customer->customer_id,
				"firstname" => $request->input("firstname"),
				"lastname" => $request->input("lastname"),
				"email" => $request->input("email"),
				"telephone" => $request->input("telephone"),
				"customer_group_id" => $customer_group,
				"postcode" => $request->input("postcode"),
				"address_line_1" => $request->input("address_line_1"),
				"address_line_2" => $request->input("address_line_2"),
				"suburb" => $request->input("suburb"),
				"state" => $request->input("state"),
				"order_membership_card" => $order_membership_card
			]]);

			return $this->responseData->success($customer);
		} catch (BadResponseException $e) {
				DB::rollBack();
				return $this->responseData->error([
					[
						array_get(json_decode($e->getResponse()->getBody()->getContents(), true), 'error_message')
					]
				]);
			}
	}

    public function generateQRCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|string|max:32',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->responseData->error($errors);
        }
        $customerId = $request->input('customer_id');
        $customer = Customer::find($customerId);
        if (!$customer) {
            $errors = array(
                "customer" => [trans('validation.customer_is_not_exist')],
            );
            return $this->responseData->error($errors);
        }

        $qrString = "{" . $customer->customer_id . "-" . $customer->hashcode . "}";
        $data = array();
        $data['qr_code'] = 'data:image/png;base64, ' . base64_encode(QrCode::format('png')->size(200)->generate($qrString));

        return $this->responseData->success($data);
    }

	public function getCustomers() {
		$user = Auth::user();
		$store = $user->store;

		$customer_group = Setting::where('store_id', '=', $store->store_id)->where('key', '=', 'CUSTOMER_GROUP_ID')->value('value');
		$query = Customer::query();
		$query->where('customer_group_id','=', $customer_group);

		$customers = $query->get();
		// add filter to link pagination

		$data = array();
		foreach ($customers as $customer) {

			$row = array(
				"customer_id" => $customer->customer_id,
				"name" => $customer->firstname . ' ' . $customer->lastname,
				"firstname" => $customer->firstname,
				"lastname" => $customer->lastname,
				"date_of_birth" => $customer->date_of_birth,
				"month_of_birth" => $customer->month_of_birth,
				"postcode" => $customer->postcode,
				"telephone" => $customer->telephone,
				"email" => $customer->email,
				"created_at" => date_format($customer->created_at,"d-m-Y H:i:s"),
				"order_membership_card" => $customer->order_membership_card,
				"address_line_1" => $customer->address_line_1,
				"address_line_2" => $customer->address_line_2,
				"suburb" => $customer->suburb,
				"state" => $customer->state
			);
			$data[] = $row;
		}

		$customers = collect($data);
		return $this->responseData->success($customers);
	}

	public function getTotalCustomers() {

		$user = Auth::user();
		$store = $user->store;

		$customer_group = Setting::where('store_id', '=', $store->store_id)->where('key', '=', 'CUSTOMER_GROUP_ID')->value('value');
		$query = Customer::query();
		$query->where('customer_group_id','=', $customer_group);
		$query->select(DB::raw("COUNT(customer_id) AS total"));
		$totalCustomers = $query->first();

		return $totalCustomers['total'];
	}

    public function getCustomerFromQRCode(Request $request) {

        $code = explode('-', $request->code);
        $customer_id = $code[0];
        $hashcode = $code[1];

        $customer = Customer::where('customer_id', '=', $customer_id)->where('hashcode', '=', $hashcode)->first();
        if ($customer) {
            $result = array(
                "customer_id" => $customer->customer_id,
                "name" => $customer->firstname . ' ' . $customer->lastname,
                "telephone" => $customer->telephone,
                "email" => $customer->email,
            );

            return $this->responseData->success($result);
        } else {
            $errors = array(
                "customer" => ["Customer not exist"],
            );
            return $this->responseData->error($errors);
        }
    }

}