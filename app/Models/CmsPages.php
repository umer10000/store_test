<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPages extends Model
{
    protected $table = "cms_pages";
    protected $fillable = ['page_name', 'heading', 'section_name'];

    public function components()
    {
        return $this->hasMany(CmsComponent::class, 'cms_page_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(CmsImage::class, 'cms_page_id', 'id');
    }
}
