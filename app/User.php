<?php

namespace App;

use App\Models\Buyer;
use App\Models\Customers;
use App\Models\Seller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function customers()
    {
        return $this->hasOne(Customers::class, 'user_id', 'id');
    }

    public function seller()
    {
        return $this->hasOne(Seller::class, 'user_id', 'id');
    }

    public function buyer()
    {
        return $this->hasOne(Buyer::class, 'user_id', 'id');
    }
}
