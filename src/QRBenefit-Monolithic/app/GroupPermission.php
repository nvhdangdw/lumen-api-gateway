<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupPermission extends Model
{
	protected $table = 'group_permission';
	protected $primaryKey = 'group_permission_id';
	protected $guarded = [];

	public function permission()
	{
		return $this->belongsTo('App\Permission', 'permission_id', 'permission_id');
	}
}
