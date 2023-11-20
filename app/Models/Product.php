<?php

namespace App\Models;

use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model implements Buyable
{
    public function getBuyableIdentifier($options = null)
    {
        return $this->id;
    }
    public function getBuyableDescription($options = null)
    {
        return $this->product_name;
    }
    public function getBuyablePrice($options = null)
    {
        return $this->product_current_price;
    }
    public function getBuyableWeight($options = null)
    {
        return 0;
    }

    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = [
        'seller_id',
        'category_id', 'sub_category_id', 'product_condition', 'product_name', 'description',
        'product_current_price', 'discount_price', 'description',
        'product_image', 'status', 'manufacturer_id', 'shipping_charges', 'location_id',
        'product_type', 'status', 'weight', 'weight_unit',
        'length', 'width', 'height',
        'dimensions_unit', 'price', 'vat', 'product_file', 'shipping', 'deleted_at', 'qty'
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }


    public function sub_category()
    {
        return $this->hasOne(Category::class, 'id', 'sub_category_id');
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function product_meta_data()
    {
        return $this->hasOne(ProductMetaData::class, 'product_id', 'id');
    }

    public function products_attributes()
    {
        return $this->hasMany(ProductAttribute::Class, 'product_id', 'id');
    }

    public function products_options()
    {
        return $this->hasMany(OptionProduct::Class, 'product_id', 'id');
    }

    public function presentPrice()
    {
        return money_format('$%i', $this->price / 100);
    }

    public function scopeMightAlsoLike($query)
    {
        return $query->inRandomOrder()->take(4);
    }

    public static function getCartOptions($option_values, $product_id)
    {

        if (isset($option_values)) {
            $options = [];
            $options_total = 0;
            $options_id = '';
            foreach ($option_values as $req_value) {
                $option_val = OptionValue::where('id', $req_value)->with('optionProduct', 'optionProduct.option')->get();
                foreach ($option_val as $i => $val) {
                    $optionProduct = OptionProduct::where('product_id', $product_id)->where('option_val_id', $val->id)->value('price');
                    $options[$val->option->slug] = $val->option_value;
                    $options_total += $optionProduct;
                    $options_id .= $req_value . ",";
                }
            }

            return [
                "options" => $options,
                "options_total" => $options_total,
                "options_id" => rtrim($options_id, ','),
            ];
        }
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id', 'id');
    }

    public function manufacturer()
    {
        return $this->hasOne(Manufacturer::class, 'id', 'manufacturer_id');
    }

    public function whishlist()
    {

        return $this->hasOne(CustomerWishlist::class, 'product_id', 'id')->where('buyer_id', Auth::user()->buyer->id);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }

    public function countryName()
    {
        return $this->hasOne(Countries::class, 'id', 'location_id');
    }

    public function specialDeals()
    {
        return $this->hasMany(SpecialDealsProducts::class, 'product_id', 'id');
    }
}
