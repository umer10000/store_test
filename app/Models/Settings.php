<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';

    public function shipping_cost(){
        return $this->hasOne(ShippingRate::class,'id','shipping_rate');
    }
}
