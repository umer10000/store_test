<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedAd extends Model
{
    protected $table = 'featured_ads';
    protected $fillable = [
        'featured_package_id', 'seller_id', 'banner', 'amount', 'start_date', 'end_date', 'status', 'is_approve'
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }
}
