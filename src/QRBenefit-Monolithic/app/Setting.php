<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	const STORE_ID = 0;
	const OC_CLIENT_ID = 'OC_CLIENT_ID';
	const OC_STORE_URL = 'OC_STORE_URL';
	const OC_CLIENT_SECRET = 'OC_CLIENT_SECRET';
	const OC_CLIENT_BASE_URL = 'OC_CLIENT_BASE_URL';
	const OC_CLIENT_ROUTE_ACCOUNT = 'OC_CLIENT_ROUTE_ACCOUNT';

	protected $table = 'setting';
	protected $primaryKey = 'setting_id';
	protected $guarded = [];

	static public function checkApiKey($apiKey)
	{
		$f = self::where('store_id', self::STORE_ID)
			->where('key', 'API_KEY')
			->where('value', $apiKey)
			->count();
		if ($f > 0) {
			return true;
		}

		return false;
	}
}
