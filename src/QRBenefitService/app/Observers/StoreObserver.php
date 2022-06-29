<?php

namespace App\Observers;

use App\Store;
use Illuminate\Support\Facades\Storage;

class StoreObserver
{
	/**
	 * Handle the user "created" event.
	 *
	 * @param  \App\User  $user
	 * @return void
	 */
	public function created(Store $store)
	{
		//
	}

	/**
	 * Handle the user "updated" event.
	 *
	 * @param  \App\User  $user
	 * @return void
	 */
	public function updated(Store $store)
	{
		//
	}

	public function deleting(Store $store)
	{

		if (!empty($store->logo) && Storage::exists('public/'.$store->logo))
			Storage::delete('public/'.$store->logo);
	}

	/**
	 * Handle the user "deleted" event.
	 *
	 * @param  \App\User  $user
	 * @return void
	 */
	public function deleted(Store $store)
	{

	}

	/**
	 * Handle the user "restored" event.
	 *
	 * @param  \App\User  $user
	 * @return void
	 */
	public function restored(Store $store)
	{
		//
	}

	/**
	 * Handle the user "force deleted" event.
	 *
	 * @param  \App\User  $user
	 * @return void
	 */
	public function forceDeleted(Store $store)
	{
		//
	}
}
