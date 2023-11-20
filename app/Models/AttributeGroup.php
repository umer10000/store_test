<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeGroup extends Model
{
    protected $table = 'attribute_group';
    protected $fillable = [
        'attribute_group', 'status'
    ];

    public function attributes(){
        return $this->hasMany(Attribute::Class,'attribute_group_id','id');
    }

}
