<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionReward extends Model
{
	protected $table = 'promotion_reward';
	protected $primaryKey = 'promotion_reward_id';
	protected $guarded = [];

	public function transaction()
	{
		return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
	}

	public function customer()
	{
		return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
	}
}
