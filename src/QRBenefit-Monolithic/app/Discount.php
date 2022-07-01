<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
	protected $table = 'discount';
	protected $primaryKey = 'discount_id';
	protected $guarded = [];

	public function store()
	{
		return $this->belongsTo('App\Store', 'store_id', 'store_id');
	}
}
