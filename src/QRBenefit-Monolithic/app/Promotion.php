<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
	protected $table = 'promotion';
	protected $primaryKey = 'promotion_id';
	protected $guarded = [];

	public function supplier()
	{
		return $this->belongsTo('App\Supplier', 'supplier_id', 'supplier_id');
	}

	public function campain()
	{
		return $this->belongsTo(Campaign::class, 'campaign_id', 'campaign_id');
	}

	public function store()
	{
		return $this->belongsTo(Store::class, 'store_id', 'store_id');
	}
}
