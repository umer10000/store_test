<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialDealsProducts extends Model
{
    protected $table = 'special_deals_products';
    protected $fillable = [
        'special_deals_id', 'seller_id', 'product_id', 'amount', 'start_date', 'end_date', 'status', 'is_approve'
    ];


    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function seller()
    {
        return $this->hasOne(Seller::class, 'id', 'seller_id');
    }
}
