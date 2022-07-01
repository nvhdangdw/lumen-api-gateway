<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignType extends Model
{
    protected $table = 'campaign_type';
	protected $primaryKey = 'campaign_type_id';
    protected $guarded = [];
    public $timestamps = false;
}
