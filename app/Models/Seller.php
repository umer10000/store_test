<?php

namespace App\Models;

use App\SellerAddress;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    protected $table = "sellers";
    protected $fillable = ['user_id', 'name', 'phone_number', 'zip_code', 'term_condition', 'profile_picture', 'cover_img','deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id', 'id');
    }

    public function sellerAddress()
    {
        return $this->hasOne(SellerAddress::class, 'seller_id', 'id');
    }
}
