<?php

namespace App;

use App\Models\Cities;
use App\Models\Countries;
use App\Models\Seller;
use App\Models\States;
use Illuminate\Database\Eloquent\Model;

class SellerAddress extends Model
{
    protected $table = "seller_addresses";
    protected $fillable = ["seller_id","first_name","last_name","email","phone_no","address1","city","company_name","zip_code","country","state"];

    public function seller(){
        return $this->belongsTo(Seller::class,'seller_id','id');
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
