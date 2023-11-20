<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    protected $table = 'option_values';
    protected $fillable = [
        'option_id','option_value', 'status'
    ];

    public function option(){
        return $this->belongsTo(Option::class, 'option_id', 'id');
    }

    public function optionProduct(){
        return $this->hasOne(OptionProduct::class, 'option_val_id', 'id');
    }

    public function optionProducts(){
        return $this->hasOne(OptionProduct::class, 'option_val_id', 'id');
    }


}
