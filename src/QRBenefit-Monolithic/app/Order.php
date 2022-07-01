<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $table = 'order';
	protected $primaryKey = 'order_id';
	protected $guarded = [];

	public function customer()
	{
		return $this->belongsTo('App\Customer', 'customer_id', 'customer_id');
	}

	public function store()
	{
		return $this->belongsTo('App\Store', 'store_id', 'store_id');
	}

	public function user()
	{
		return $this->belongsTo('App\User', 'user_id', 'id');
	}

    public function orderDiscounts()
	{
		return $this->hasMany('App\OrderDiscount','order_id', 'order_id');
	}
}
