<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'options';
    protected $fillable = [
        'option_name', 'status', 'slug'
    ];

    public function optionValues(){
        return $this->hasMany(OptionValue::Class,'option_id','id');
    }

}
