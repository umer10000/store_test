<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    protected $table = "countries";

    protected $fillable = ['country_name','status'];

    public function states(){
        return $this->hasMany(States::class,'country_id','id');
    }
}
