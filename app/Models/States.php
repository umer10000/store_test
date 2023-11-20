<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    protected $table = "states";

    protected $fillable = ['country_id', 'state_name', 'status'];


    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id', 'id');
    }
}
