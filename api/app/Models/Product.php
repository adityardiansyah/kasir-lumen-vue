<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['store_id', 'category_id', 'name', 'price_buy', 'price_sell', 'fee_reseller', 'image','stock'];
}
