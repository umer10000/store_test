<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsComponent extends Model
{
    protected $table = "cms_components";
    protected $fillable = ['cms_page_id', 'title', 'description'];
}
