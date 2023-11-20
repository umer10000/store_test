<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attribute';
    protected $fillable = [
        'attribute_group_id','attribute_name', 'status'
    ];

    public function attributeGroup(){
        return $this->hasOne(AttributeGroup::class, 'id', 'attribute_group_id');
    }

}
