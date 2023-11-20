<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $table = 'shipping_rates';
    protected $fillable = [
        'rate', 'status'
    ];


}
