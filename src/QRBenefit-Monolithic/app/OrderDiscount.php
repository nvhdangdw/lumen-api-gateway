<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDiscount extends Model
{
	protected $table = 'order_discount';
	protected $primaryKey = 'order_discount_id';
	protected $guarded = [];

	public function discount()
	{
		return $this->belongsTo('App\Discount', 'discount_id', 'discount_id');
	}
}
