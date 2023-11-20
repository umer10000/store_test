<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShippingLabel extends Model
{
    protected $table = "order_shipping_labels";
    protected $fillable = [
        'order_item_id', 'tracking_number', 'shipping_doc_name'
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'id');
    }
}
