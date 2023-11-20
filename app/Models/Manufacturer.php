<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $table = "manufacturers";
    protected $fillable = [
        'name', 'image', 'status', 'sort_order'
    ];
    public $timestamps = false;
}
