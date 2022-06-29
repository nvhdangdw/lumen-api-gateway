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

	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'firstname' => 'required|string|max:30',
			'lastname' => 'required|string|max:30',
			'email' => 'required|unique:customer|email|max:50',
			'telephone' => [
				'required',
				'regex:/^([\d +()]{8,32})$/i',
				'string',
				'min:8',
				'max:32'
			],
			'customer_group_id' => 'required',
		]);
		if ($validator->fails()) {
			$errors = $validator->errors();
			return $this->responseData->error($errors);
		}
        try {
            DB::beginTransaction();

            $customer = new Customer;
            $customer->firstname = $request->input("firstname");
            $customer->lastname = $request->input("lastname");
            $customer->email = $request->input("email");
            $customer->telephone = $request->input("telephone");
            $customer->customer_group_id = $request->input("customer_group_id");
            $customer->save();
            $customer->hashcode = sha1($customer->customer_id);
            $customer->save();

            $clientBaseUrl = Setting::where('store_id', Setting::STORE_ID)->where('key', Setting::OC_CLIENT_BASE_URL)->value('value');
            $client = new Client(['base_uri' => $clientBaseUrl]);
            $clientId = Setting::where('store_id', Setting::STORE_ID)->where('key', Setting::OC_CLIENT_ID)->value('value');
            $clientSecret = Setting::where('store_id', Setting::STORE_ID)->where('key', Setting::OC_CLIENT_SECRET)->value('value');
            $clientRouteAccount = Setting::where('store_id', Setting::STORE_ID)->where('key', Setting::OC_CLIENT_ROUTE_ACCOUNT)->value('value');

            $response = $client->request('POST', $clientRouteAccount, ['form_params' => [
                "client_id" => $clientId,
                "client_secret" => $clientSecret,
                "qr_customer_id" => $customer->customer_id,
                "firstname" => $request->input("firstname"),
                "lastname" => $request->input("lastname"),
                "email" => $request->input("email"),
                "telephone" => $request->input("telephone"),
                "customer_group_id" => $request->input("customer_group_id")
            ]]);
            $responseData = json_decode($response->getBody()->getContents(), true);

            $customer->external_id = array_get($responseData,'oc.customer_id');
            $customer->save();

            DB::commit();
            Mail::send(new AccountCreated($request->input("email")));
            return $this->responseData->success($customer);
        } catch (BadResponseException $e) {
            DB::rollBack();
            return $this->responseData->error([
                [
					array_get(json_decode($e->getResponse()->getBody()->getContents(), true),'error_message')
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
}
