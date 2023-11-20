<?php

use Carbon\Carbon;

function presentPrice($price)
{
    try {
        return '$' . $price;
    } catch (Exception $ex) {
        return '$' . $price;
    }
}

function presentDate($date)
{
    return Carbon::parse($date)->format('M d, Y');
}

function setActiveCategory($category, $output = 'active')
{
    return request()->category == $category ? $output : '';
}

function productImage($path)
{
    return $path && file_exists('uploads/products/' . $path) ? asset('uploads/products/' . $path) : asset('admin/images/placeholder.png');
}

function featuredAddImage($path)
{
    return $path && file_exists('uploads/featured_ads/' . $path) ? asset('uploads/featured_ads/' . $path) : asset('admin/images/placeholder.png');
}

function categoryImage($path)
{
    return $path && file_exists('uploads/category/' . $path) ? asset('uploads/category/' . $path) : asset('admin/images/placeholder.png');
}

function sellerProfilePicture($path)
{
    return $path && file_exists('uploads/seller/' . $path) ? asset('uploads/seller/' . $path) : asset('images/userimg.png');
}

function buyerProfilePicture($path)
{
    return $path && file_exists('uploads/buyer/' . $path) ? asset('uploads/buyer/' . $path) : asset('images/userimg.png');
}

function sellerCoverPicture($path)
{
    return $path && file_exists('uploads/seller/' . $path) ? asset('uploads/seller/' . $path) : asset('images/cover.jpg');
}

function manufacturerImage($path)
{
    return $path && file_exists('uploads/manufacturer/' . $path) ? asset('uploads/manufacturer/' . $path) : asset('admin/images/placeholder.png');
}

function getNumbers()
{
    $tax = config('cart.tax') / 100;
    $discount = session()->get('coupon')['discount'] ?? 0;
    $code = session()->get('coupon')['name'] ?? null;
    $newSubtotal = (Cart::subtotal() - $discount);
    if ($newSubtotal < 0) {
        $newSubtotal = 0;
    }
    $newTax = $newSubtotal * $tax;
    $newTotal = $newSubtotal * (1 + $tax);

    return collect([
        'tax' => $tax,
        'discount' => $discount,
        'code' => $code,
        'newSubtotal' => $newSubtotal,
        'newTax' => $newTax,
        'newTotal' => $newTotal,
    ]);
}

function getStockLevel($quantity)
{
    if ($quantity > setting('site.stock_threshold', 5)) {
        $stockLevel = '<div class="badge badge-success">In Stock</div>';
    } elseif ($quantity <= setting('site.stock_threshold', 5) && $quantity > 0) {
        $stockLevel = '<div class="badge badge-warning">Low Stock</div>';
    } else {
        $stockLevel = '<div class="badge badge-danger">Not available</div>';
    }

    return $stockLevel;
}

function breakDescription2WithItems($str)
{
    $h3toh5 = str_replace('<h3>', '<h5>', $str);
    $h3toh5 = str_replace('</h3>', '</h5>', $h3toh5);
    $substrs = explode('</p>',  $h3toh5);
    $final = array();
    foreach ($substrs as $substr) {
        $final[] = $substr . '</p>';
    }

    return $final;
}
