<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMetaData extends Model
{
    protected $table = 'products_meta_data';
    protected $fillable = [
        'product_id','meta_tag_title','meta_tag_description', 'meta_tag_keywords','status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::Class, 'id', 'product_id');
    }
}
