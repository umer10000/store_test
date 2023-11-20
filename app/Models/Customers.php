<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table='customers';
    protected $fillable = ['user_id','first_name','last_name','email','phone_no','city','state','country','address','status','gender','notification_check'];

    public function reviews(){
        return $this->hasMany(ProductReview::Class,'id','customer_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function addresses(){
        return $this->hasMany(CustomerAddress::class, 'customer_id', 'id');
    }
    
}
