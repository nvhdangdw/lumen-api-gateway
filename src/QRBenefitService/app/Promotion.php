<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
	protected $table = 'discount';
	protected $primaryKey = 'discount_id';

	public function supplier()
	{
		return $this->belongsTo('App\Supplier', 'supplier_id', 'supplier_id');
	}
}