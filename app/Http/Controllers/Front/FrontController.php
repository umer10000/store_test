<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Cities;
use App\Models\CmsPages;
use App\Models\Countries;
use App\Models\FeaturedAd;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\NewsLetter;
use App\Models\Seller;
use App\Models\SpecialDealsProducts;
use App\Models\States;
use App\Models\Banner;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(Request $request)
    {


        $productsByCategories = Category::where('status', 1)->where('orderbyshopnow', '>', 0)->where('is_shop_now', 1)->with(['subCategoryproduct', 'products', 'products' => function ($q) {
            $q->where('status', '1')->where('deleted_at', null)->whereHas('seller', function ($q) {
                $q->where('deleted_at', null);
            })->limit(15);
        }])->orderBy('orderbyshopnow', 'ASC')->limit(12)->get();


        $products = Product::where('deleted_at', null)->where('status', 1)->whereHas('seller.sellerAddress')
            ->whereHas('seller', function ($q) {
                $q->where('deleted_at', null);
            })
            ->whereHas('category', function ($q) {
                $q->where('status', 1);
            })->with(['sub_category' => function ($q) {
                $q->where('status', 1);
            }])
            ->orderBy('id', 'desc')->take(16)
            ->get();

        $featuredads = FeaturedAd::where('status', 1)
            ->where('is_approve', 'yes')
            ->whereDate('end_date', '>=', date('Y-m-d'))
            ->orderBy('id', 'asc')->take(20)
            ->get();

        $dealsProducts = SpecialDealsProducts::whereHas('product', function ($q) {
            $q->where('status', 1)->where('deleted_at', null);
        })->where('status', 1)
            ->where('is_approve', 'yes')
            ->whereDate('end_date', '>=', date('Y-m-d'))
            ->inRandomOrder()->limit(50)
            ->get();

        $topCategories = Category::where('status', 1)->where('is_top_cat', 1)->where('orderbytopcategory', '>', 0)
            //->orderBy('id', 'desc')->take(12)
            ->orderBy('orderbytopcategory', 'ASC')->take(12)
            ->get();

        $firstSection = CmsPages::where('section_name', 'first')->with('components')->first();
        $secondSection = CmsPages::where('section_name', 'second')->with('images', 'components')->first();

        $thirdSection = CmsPages::where('section_name', 'third')->with('images', 'components')->first();

        $banner = Banner::where('name', 'home banner')->first();

        return view('front.index', compact(
            'productsByCategories',
            'products',
            'dealsProducts',
            'featuredads',
            'topCategories',
            'firstSection',
            'secondSection',
            'thirdSection',
            'banner'
        ));
        // $thirdSection = CmsPages::where('section_name', 'third')->with('images', 'components')->first();

        // return view('front.index', compact(
        //     'productsByCategories',
        //     'products',
        //     'dealsProducts',
        //     'featuredads',
        //     'topCategories',
        //     'firstSection',
        //     'secondSection',
        //     'thirdSection'
        // ));
    }

    /*
     * Newsletter Subscription */
    public function subscribeNewsletter(Request $request)
    {
        try {
            if ($request->method() == 'POST') {
                $email = $request->email;
                $json = array('status' => false);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $json['error'] = "Error: Invalid email format";
                }

                if (empty($email) || $email == null || $email == '') {
                    $json['error'] = "Error: Please type correct email address";
                }

                $alreadyExists = NewsLetter::where('email', $email)->first();
                if (!empty($alreadyExists) > 0) {
                    $json['error'] = "Error: You have already subscribed";
                }
                if (!isset($json['error'])) {
                    NewsLetter::create([
                        'email' => $email,
                        'status' => 1
                    ]);
                    $json['status'] = true;
                    $json["success"] =  "Success: You Have Successfully Subscribed";
                }
                return $json;
            }
        } catch (\Exception $ex) {
            $json = array();
            $json['status'] = false;
            $json['error'] = "Whoops!! Something went wrong ";
            return $json;
        }
    }

    public function sellerProfile(Request $request, $id)
    {
        $seller = Seller::where('id', $id)->firstOrFail();

        $products = Product::query();
        $pagination = 9;
        $parent_category = "";

        if (request()->mainCategory) {
            $products = $products->whereIn('category_id', explode(',', $request->mainCategory));
        }

        if (request()->subCategory) {
            $products = $products->whereIn('sub_category_id', explode(',', $request->subCategory));
        }

        if (request()->condition) {
            $products = $products->whereIn('product_condition', explode(',', $request->condition));
        }

        if (request()->location) {
            $products = $products->whereIn('location_id', explode(',', $request->location));
        }

        if (request()->mainCategory) {
            $products = $products->whereIn('category_id', explode(',', $request->mainCategory));
        }

        if (request()->price) {
            $price = explode('-', request()->price);
            $products = $products->whereBetween('product_current_price', [$price[0], $price[1]]);
        }

        $products = $products->where('seller_id', $seller->id)->where('status', 1)->where('deleted_at', null)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with(['sub_category' => function ($q) {
            $q->where('status', 1);
        }])->paginate(12);
        $usedProducts = Product::where('seller_id', $seller->id)->where('status', 1)->where('deleted_at', null)->where('product_condition', 'new')->count();
        $newProducts = Product::where('seller_id', $seller->id)->where('status', 1)->where('deleted_at', null)->where('product_condition', 'used')->count();

        $categories = Category::where('status', 1)->where('parent_id', 0)->with('subCategories')->orderby('name')->get();
        $subCategories = Category::where('status', 1)->where('parent_id', '!=', 0)->with('subCategories')->orderby('name')->get();
        $locations = Countries::all();
        $manufacturers = Manufacturer::where('status', 1)->get();

        //        return view('front.shop.index',compact('products','categories','manufacturers','usedProducts','newProducts','locations'));

        //        $products = Product::where('seller_id',$seller->id)->where('status',1)->paginate(12);

        return view('front.seller.seller-profile', compact('usedProducts', 'newProducts', 'seller', 'categories', 'locations', 'products', 'subCategories'));
    }


    public function getCountries()
    {
        $countries = Countries::select('id', 'name')->get();
        return $data = array('status' => 'success', 'tp' => 1, 'msg' => "Countries fetched successfully.", 'result' => $countries);
    }

    public function getStates($countryId)
    {
        $states = States::select('id', 'name')->where('country_id', $countryId)->get();
        return $data = array('status' => 'success', 'tp' => 1, 'msg' => "Countries fetched successfully.", 'result' => $states);
    }

    public function getCities($stateId)
    {
        $cities = Cities::select('id', 'name')->where('state_id', $stateId)->get();
        return $data = array('status' => 'success', 'tp' => 1, 'msg' => "Countries fetched successfully.", 'result' => $cities);
    }

    public function termCondition()
    {
        $termsandconditions = CmsPages::where('section_name', 'termsandconditions')->with('components')->first();
        return view('front.term-condition', compact('termsandconditions'));
    }
}
