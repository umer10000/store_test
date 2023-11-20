<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWishlist extends Model
{
    protected $table = "customer_wishlist";
    protected $fillable = ['buyer_id', 'product_id'];

    public function product(){
        return $this->hasOne(Product::class, 'id' ,'product_id');
    }
}
