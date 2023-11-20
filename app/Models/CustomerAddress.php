<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $table = "customer_addresses";
    protected $fillable = ["customer_id","first_name","last_name","email","phone_no","address1","address2","city","company_name","zip_code","country","state"];

    public function buyer(){
        return $this->belongsTo(Buyer::class,'customer_id','id');
    }

    public function countryName(){
        return $this->hasOne(Countries::class,'id','country');
    }

    public function cityName(){
        return $this->hasOne(Cities::class,'id','city');
    }

    public function stateName(){
        return $this->hasOne(States::class,'id','state');
    }

}
