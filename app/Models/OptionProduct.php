<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionProduct extends Model
{
    protected $table = 'option_products';
    protected $fillable = [
        'product_id', 'option_id','option_val_id','price','qty'
    ];

    public function product(){
        return $this->belongsTo(Product::Class,'id','product_id');
    }

    public function option(){
        return $this->hasOne(Option::Class,'id','option_id');
    }

    public function option_val(){
        return $this->hasMany(OptionValue::Class,'id','option_val_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'option_id', 'option_val_id');
    }

}
