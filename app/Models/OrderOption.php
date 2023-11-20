<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderOption extends Model
{
    protected $table = 'order_options';
    protected $fillable = [
        'order_id', 'option_id','order_qty','option_name'
    ];
}
