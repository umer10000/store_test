<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images';

    protected $fillable = [
        'product_id', 'product_images', 'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::Class, 'id', 'product_id');
    }
}
