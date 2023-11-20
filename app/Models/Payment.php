<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = "payments";
    protected $fillable = [
        'order_id', 'amount', 'card_id', 'pay_method_name', 'special_deal_product_id', 'featured_ad_id', 'seller_id', 'description', 'pay_method_name'
    ];
}
