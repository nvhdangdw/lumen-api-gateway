<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpencartCustomer extends Model
{
	protected $connection = 'opencart';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = null;
	protected $table = 'customer';
	protected $primaryKey = 'customer_id';

	public $attributes = [
		'status' => 1,
		'store_id' => 0,
		'language_id' => 1,
		'fax' => "",
		'password' => "",
		'salt' => "",
		'custom_field' => "",
		'ip' => "",
		'safe' => 0,
		'token' => "",
		'code' => "",
	];
}
