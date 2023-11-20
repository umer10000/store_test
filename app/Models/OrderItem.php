<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $fillable = [
        'order_id', 'product_id', 'seller_id', 'variant_id', 'product_per_price', 'product_qty', 'length', 'width',
        'height', 'weight', 'tax', 'product_subtotal_price',
        'status', 'is_archive', 'deleted_at', 'service_charges', 'tracking_number', 'shipping_doc_name',
        'shipping_cost', 'shipping_name', 'packaging_type', 'product_type', 'downloaded', 'shipping_type'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function seller()
    {
        return $this->hasOne(Seller::class, 'id', 'seller_id');
    }

    public function shippingLabels()
    {
        return $this->hasMany(OrderShippingLabel::class);
    }
}
