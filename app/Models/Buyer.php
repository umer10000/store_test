<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    protected $table = "buyers";
    protected $fillable = ['user_id', 'name', 'phone_number', 'zip_code', 'term_condition', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buyerAddress()
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id', 'id');
    }
    public function buyerOrders()
    {
        return $this->hasMany(Order::class, 'buyer_id', 'id');
    }
}
