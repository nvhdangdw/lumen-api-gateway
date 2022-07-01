<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignStatus extends Model
{
    protected $table = 'campaign_status';
	protected $primaryKey = 'campaign_status_id';
    protected $guarded = [];
    public $timestamps = false;
}
