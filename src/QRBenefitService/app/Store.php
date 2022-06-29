<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
	protected $table = 'store';
	protected $primaryKey = 'store_id';

	public function settings()
	{
		return $this->hasMany('App\Setting','store_id', 'store_id');
	}

	public function tfaEnabled() {
		$setting =  $this->settings()->where('key','=', 'TFA_ENABLED')->first();
		if ($setting) {
			return $setting->value;
		}
		return null;
	}
	public function domainUrl() {
		$setting =  $this->settings()->where('key','=', 'DOMAIN_URL')->first();
		if ($setting) {
			return $setting->value;
		}
		return null;
	}
}
