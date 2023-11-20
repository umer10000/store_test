<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'order_no', 'seller_id', 'buyer_id', 'buyer_name', 'buyer_email', 'sub_total', 'tax', 'total_amount', 'address1', 'address2',
        'order_status', 'status', 'discount', 'shipping_cost', 'shipping_name', 'phone_no', 'country', 'state', 'city',
        'zip', 'note', 'term_accepted', 'is_archive', 'deleted_at', 'service_charges', 'packaging_type', 'tracking_number', 'shipping_doc_name', 'vat_charges'
    ];

    public function buyer()
    {
        return $this->hasOne(Buyer::class, 'id', 'buyer_id');
    }

    public function seller()
    {
        return $this->hasOne(Seller::class, 'id', 'seller_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function country_name()
    {
        return $this->hasOne(Countries::class, 'id', 'country');
    }

    public function state_name()
    {
        return $this->hasOne(States::class, 'id', 'state');
    }

    public function city_name()
    {
        return $this->hasOne(Cities::class, 'id', 'city');
    }
}
