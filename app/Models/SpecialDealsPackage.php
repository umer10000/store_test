<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialDealsPackage extends Model
{

    protected $table = 'special_deals_packages';
    protected $fillable = [
        'name', 'days', 'amount'
    ];
}
