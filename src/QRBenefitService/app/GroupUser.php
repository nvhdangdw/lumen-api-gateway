<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
	protected $table = 'group_user';
	protected $primaryKey = 'group_user_id';

	public function groupPermissions()
	{
		return $this->hasMany('App\GroupPermission','group_user_id', 'group_user_id');
	}
}
