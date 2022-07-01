<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $table = 'product';
	protected $primaryKey = 'product_id';
    protected $guarded = [];
    public $timestamps = false;

}
