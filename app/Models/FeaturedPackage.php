<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedPackage extends Model
{
    protected $table = 'featured_packages';
    protected $fillable = [
        'name', 'days', 'amount'
    ];
}
