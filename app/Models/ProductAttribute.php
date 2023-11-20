<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $table='attribute_product';

    protected $fillable = [
        'product_id','attribute_id','value'
    ];

    public function products()
    {
        return $this->hasMany(Product::Class, 'id', 'product_id');
    }

    public function attribute(){
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }
}
