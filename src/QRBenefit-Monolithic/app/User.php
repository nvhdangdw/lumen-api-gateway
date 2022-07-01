<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\AccountRecovery;

class User extends Authenticatable implements JWTSubject
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password','group_user_id'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function groupUser()
	{
		return $this->belongsTo('App\GroupUser', 'group_user_id', 'group_user_id');
	}

	public function store()
	{
		return $this->belongsTo('App\Store', 'store_id', 'store_id');
	}

	/**
	 * Send the password reset notification.
	 *
	 * @param  string  $token
	 * @return void
	 */
	public function sendPasswordResetNotification($token)
	{
		if ($this->group_user_id == 1) {
			$url = route('admin.show_reset_form', $token);
			$this->notify(new \App\Notifications\Admin\PasswordReset($url, $this));

		} else {
			$url =$this->store->domainUrl().'/auth/reset-password/'.$token;
			$this->notify(new \App\Notifications\Admin\PasswordReset($url, $this));
			$accountRecovery = new AccountRecovery;
			$accountRecovery->email = $this->email;
			$accountRecovery->token = $token;
			$accountRecovery->save();

		}
	}

	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	public function getJWTCustomClaims()
	{
		return [];
	}

	public function getAvatarUrlAttribute()
	{
		$path = 'user/avatar/' . $this->avatar;
		return (trim($this->avatar) && Storage::disk('public')->exists($path)) ? asset(Storage::url($path)) : asset(Storage::url('default/user.png'));
	}

	public function getThumbAvatarUrlAttribute()
	{
		$path = 'user/avatar/thumb-' . $this->avatar;
		if(!Storage::disk('public')->exists($path))
			$path = 'user/avatar/' . $this->avatar;
		return (trim($this->avatar) && Storage::disk('public')->exists($path)) ? asset(Storage::url($path)) : asset(Storage::url('default/user.png'));
	}
}
