<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'campaign';
	protected $primaryKey = 'campaign_id';
    protected $guarded = [];

    public function campaignType() {
        return $this->belongsTo(CampaignType::class);
    }

    public function campaignStatus() {
        return $this->belongsTo(CampaignStatus::class);
    }
}
