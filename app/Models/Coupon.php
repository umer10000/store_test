<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = "coupons";

    protected $fillable = [
        'customer_id', 'code', 'value', 'type', 'expiration_date', 'usage', 'status', 'used'
    ];
}
