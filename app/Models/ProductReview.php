<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $table = 'product_reviews';
    protected $fillable = [
        'product_id', 'buyer_id', 'author',
        'description', 'rating',
        'status'
    ];

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function buyer(){
        return $this->belongsTo(Buyer::class,'buyer_id','id');
    }
}
