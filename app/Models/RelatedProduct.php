<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedProduct extends Model
{
    protected $table = 'related_products';
    protected $fillable = [
        'product_id', 'related_id'
    ];

    public function products(){
        return $this->hasMany(Product::class , 'id', 'related_id');
    }

}
