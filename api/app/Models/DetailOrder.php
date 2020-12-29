<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    protected $table = 'order_details';
    
    protected $fillable = ['order_id', 'list_order'];
}
