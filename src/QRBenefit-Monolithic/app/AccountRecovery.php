<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountRecovery extends Model
{
	protected $table = 'account_recovery';
	protected $primaryKey = 'account_recovery_id';
	protected $guarded = [];
}
