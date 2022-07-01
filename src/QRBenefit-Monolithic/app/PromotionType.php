<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionType extends Model
{
    protected $table = 'promotion_type';
	protected $primaryKey = 'promotion_type_id';
    protected $guarded = [];

    public function rewardExpiryMode()
    {
        return $this->belongsTo(RewardExpiryMode::class, 'reward_expiry_mode_id', 'reward_expiry_mode_id');
    }
}
