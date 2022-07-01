<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardExpiryMode extends Model
{
    protected $table = 'reward_expiry_mode';
	protected $primaryKey = 'reward_expiry_mode_id';
    protected $guarded = [];
    public $timestamps = false;
}
