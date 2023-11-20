<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Countries;
use App\Models\CustomerWishlist;
use App\Models\Manufacturer;
use App\Models\OptionProduct;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\RelatedProduct;
use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Str;
use App\Models\Banner;
use App\Models\Cities;
use App\Models\CustomerAddress;
use App\Models\Settings;
use App\Models\States;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query();
        $pagination = 9;
        $parent_category = "";
        $categoryName = 'Filtered Results';
        $categoryImg = null;
        $setting = Settings::find(1);
        if (request()->has('query')) {
            $query = $request->input('query');
            $categoryName = 'Search Results';
            $products = $products->where('product_name', 'like', '%' . $query . '%');
        }

        if (request()->category) {
            // dd(request()->category);
            $products = $products->with('sub_category')->whereIn('sub_category_id', explode(',', $request->category));

            if (Str::contains(request()->category, ',')) {
                $categoryName = 'Filtered Results';
            } else {
                $categoryObj = Category::where('id', $request->category)->first();
                $categoryName = $categoryObj->name;
                $categoryImg = $categoryObj->category_banner;
            }
        }

        if (request()->brand) {
            $products = $products->with('manufacturer')->whereIn('manufacturer_id', explode(',', $request->brand));
        }

        if (request()->condition) {
            $products = $products->whereIn('product_condition', explode(',', $request->condition));
        }

        if (request()->location) {
            $products = $products->whereIn('location_id', explode(',', $request->location));
        }

        if (request()->mainCategory) {
            $products = $products->whereIn('category_id', explode(',', $request->mainCategory));
            if (Str::contains(request()->category, ',')) {
                $categoryName = 'Filtered Results';
            } else {
                $categoryObj = Category::where('id', $request->mainCategory)->first();
                $categoryName = $categoryObj->name;
                $categoryImg = $categoryObj->category_banner;
            }
        }

        if (request()->price) {
            $price = explode('-', request()->price);

            $price1 = ($price[0] / 100) * $setting->service_charges;
            $priceR1 = $price[0] - $price1;

            $price2 = ($price[1] / 100) * $setting->service_charges;
            $priceR2 = $price[1] - $price2;

            $products = $products->whereBetween('price', [$priceR1, $priceR2]);
        }

        if (request()->shipping && request()->shipping == "any_amount") {
            $products = $products->where('shipping', 1);
        }
        if (request()->shipping && request()->shipping == "free_shipping") {
            $products = $products->where('shipping', 2);
        }

        if (request()->sort == 'low_high') {
            $products = $products->orderBy('price', 'asc');
        } elseif (request()->sort == 'high_low') {
            $products = $products->orderBy('price', 'desc');
        } elseif (request()->sort == 'new_old') {
            $products = $products->orderBy('id', 'desc');
        } elseif (request()->sort == 'old_new') {
            $products = $products->orderBy('id', 'asc');
        }

        $products = $products->where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->whereHas('seller', function ($q) {
            $q->where('deleted_at', null);
        })->with(['sub_category' => function ($q) {
            $q->where('status', 1);
        }])->where('deleted_at', null)->paginate(12);

        $usedProducts = $products->where('status', 1)->where('product_condition', 'used')->where('deleted_at', null)->count();
        $newProducts = $products->where('status', 1)->where('product_condition', 'new')->where('deleted_at', null)->count();

        $locations = Countries::all();
        $categories = Category::where('status', 1)->where('parent_id', 0)->with('subCategories')->orderby('name')->get();
        $manufacturers = Manufacturer::where('status', 1)->orderBy('sort_order', 'asc')->get();
        $countries = Countries::where('status', 1)->get();
        $banner = Banner::where('name', 'search banner')->first();

        return view('front.shop.index', compact('banner', 'products', 'categories', 'manufacturers', 'usedProducts', 'newProducts', 'locations', 'categoryName', 'categoryImg', 'countries'));
    }

    public static function paginate(Collection $results, $pageSize)
    {
        $page = Paginator::resolveCurrentPage('page');

        $total = $results->count();

        return self::paginator($results->forPage($page, $pageSize), $total, $pageSize, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }


    protected static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }
    public function show($id)
    {
        $product = Product::where('id', $id)->where('deleted_at', null)->whereHas('seller', function ($q) {
            $q->where('deleted_at', null);
        })->with('seller', 'product_images', 'products_attributes', 'products_attributes.attribute', 'products_options')->where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1)->where('deleted_at', null);
        })->with(['sub_category' => function ($q) {
            $q->where('status', 1);
        }])->firstOrFail();

        $options = [];
        foreach ($product->products_options as  $option) {
            if (!isset($options[$option->option_id])) {
                $options[$option->option_id] = [
                    "name" => $option->option->option_name,
                    "options" => [],
                ];
            }
            array_push($options[$option->option_id]['options'], $option->option_val[0]);
        }


        $mightAlsoLike = Product::where('category_id', $product->category_id)->where('deleted_at', null)->whereHas('seller', function ($q) {
            $q->where('deleted_at', null);
        })->where('id', '!=', $product->id)->where('status', 1)
            ->whereHas('category', function ($q) {
                $q->where('status', 1);
            })->with(['sub_category' => function ($q) {
                $q->where('status', 1);
            }])->orderBy('id', 'desc')->limit(25)->get();


        if ($product->product_qty > 0) {
            $stockLevel = '<div class="badge badge-success">In Stock</div>';
        } else {
            $stockLevel = '<div class="badge badge-danger">Not available</div>';
        }

        $productReviews = ProductReview::with('buyer')->where('status', 1)->where('product_id', $product->id)->get();
        $product_id = $id;

        //code here
        if (Auth::user() && Auth::user()->role_id == 3) {
            $shippingAddress = CustomerAddress::where('customer_id', Auth::user()->buyer->id)->first();
            if ($shippingAddress) {
                $state = States::where('id', $shippingAddress->state)->first();
                $city = Cities::where('id', $shippingAddress->city)->first();
            } else {
                $shippingAddress = [];
                $state = [];
                $city = [];
            }
        } else {
            $shippingAddress = [];
            $state = [];
            $city = [];
        }

        $settings = Settings::with('shipping_cost')->first();
        $countries = Countries::all();

        return view('front.shop.show')->with([
            'product' => $product,
            'stockLevel' => $stockLevel,
            'mightAlsoLike' => $mightAlsoLike,
            'options' => $options,
            'productReviews' => $productReviews,
            'product_id' => $product_id,
            'countries' => $countries,
            'state' => $state,
            'city' => $city,
        ]);
    }

    public function checkProductPrice(Request $request)
    {

        $product = Product::find($request->product_id);
        $price = OptionProduct::where('product_id', $product->id)->where('option_val_id', $request->product_option_id)->value('price');

        return $price + $product->product_current_price;
    }

    // add Product review
    public function addReview(Request $request, $id)
    {

        try {
            if ($request->method() == 'POST') {
                $user_id = 0;
                $json = array();
                // Auth::user()->buyer->id;

                if (Auth::check()) {

                    $user_id = Auth::user()->buyer->id;
                }
                if ((strlen($request->name) < 3) || (strlen($request->name) > 25)) {
                    $json['error'] = "Warning: Review Name must be between 3 and 25 characters!";
                }

                if ((strlen($request->text) < 25) || (strlen($request->text) > 1000)) {
                    $json['error'] = "Warning: Review Text must be between 25 and 1000 characters!";
                }

                if (!isset($request->rating) || $request->rating < 0 || $request->rating > 5) {
                    $json['error'] = "Warning: Please select a review rating!";
                }


                if (!isset($json['error'])) {
                    ProductReview::create([
                        'product_id' => $id,
                        'buyer_id' => $user_id,
                        'author' => $request->name,
                        'description' => $request->text,
                        'rating' => $request->rating,
                        'status' => 0
                    ]);
                    $json['success'] = "Thank you for your review. It has been submitted to the webmaster for approval.";
                }

                return $json;
            }
        } catch (\Exception $ex) {
            return redirect()->back();
        }
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|min:3',
        ]);

        $query = $request->input('query');

        $products = Product::where('product_name', 'like', '%' . $query . '%')->where('deleted_at', null)->where('status', 1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with(['sub_category' => function ($q) {
            $q->where('status', 1);
        }])->paginate(12);

        $categories = Category::where('status', 1)->get();
        return view('front.search-result', compact('products', 'categories'));
    }

    public function view_wishlist()
    {
        $products = CustomerWishlist::where('customer_id', Auth::id())->with('product')->get();
        // dd($products);
        return view('front.wishlist', compact('products'));
    }


    public function add_wishlist(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            "product_id" => "required",
            "customer_id" => "required",
        ]);
        if ($validator->fails()) {
            return [
                "status" => false,
                "message" => "Unfortunately wishlist not added.",
                "errors" => $validator->messages()
            ];
        }
        if (isset($data['remove']) && $data['remove'] == 1) {
            CustomerWishlist::where('id', $data['wishlist_id'])->delete();
            return [
                "status" => true,
                "message" => "Wishlist removed Successfully!",
                "errors" => []
            ];
        }

        $data = CustomerWishlist::create([
            "product_id" => $data['product_id'],
            "customer_id" => $data['customer_id'],
        ]);

        return [
            "status" => true,
            "message" => "Wishlist Added Successfully!",
            "errors" => [],
            "data" => $data
        ];
    }

    public function sellerProducts()
    {
    }
}
