<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsImage extends Model
{
    protected $table = "cms_images";
    protected $fillable = ['cms_page_id', 'cms_component_id', 'image_type', 'image'];
}
