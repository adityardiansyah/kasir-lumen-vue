<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['store_id', 'customer_id', 'invoice', 'total','fee_reseller','payment','cashback'];
}
