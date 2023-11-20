<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Seller;
use App\Models\Category;
use App\Models\Cities;
use App\Models\Countries;
use App\Models\CustomerAddress;
use App\Models\Customers;
use App\Models\CustomerWishlist;
use App\Models\FeaturedAd;
use App\Models\FeaturedPackage;
use App\Models\Manufacturer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderShippingLabel;
use App\Models\Payment;
use App\Models\Product;
use App\Models\SpecialDealsPackage;
use App\Models\SpecialDealsProducts;
use App\Models\States;
use App\SellerAddress;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class SellerController extends Controller
{
    public function dashboard()
    {
        $seller_id = Auth::user()->seller->id;
        $orders = Order::whereHas('orderItems', function ($q) use ($seller_id) {
            $q->where('seller_id', $seller_id)->whereNull('deleted_at')->whereNull('is_archive')->with(['product', 'product.category']);
        })
            ->with('buyer')
            ->orderBy('id', 'desc')
            ->get();

        $archiveOrders = Order::whereHas('orderItems', function ($q) use ($seller_id) {
            $q->where('seller_id', $seller_id)->whereNotNull('is_archive')->whereNull('deleted_at')->with(['product', 'product.category']);
        })
            ->with('buyer')
            ->orderBy('id', 'desc')
            ->get();

        $mainCategories = Category::where('status', 1)->where('parent_id', 0)->get();
        $subCategories = Category::where('status', 1)->whereNotNull('parent_id')->get();

        $products = Product::where('seller_id', Auth::user()->seller->id)->whereStatus(1)->whereHas('category', function ($q) {
            $q->where('status', 1);
        })->with(['category', 'specialDeals', 'sub_category' => function ($q) {
            $q->where('status', 1);
        }])->orderBy('id', 'desc')->get();

        $specialDealsPackages = SpecialDealsPackage::all();
        $featuredPackages = FeaturedPackage::all();
        $specialDealsProducts = SpecialDealsProducts::where('seller_id', Auth::user()->seller->id)->where('end_date', '>', date('Y-m-d'))->get()->pluck('product_id')->toArray();
        $manufacturers = Manufacturer::whereStatus(1)->get();
        $countries = Countries::whereStatus(1)->get();
        $addresses = SellerAddress::where('seller_id', Auth::user()->seller->id)->with('countryName', 'cityName', 'stateName')->get();

        // dd($addresses);

        return view('seller.dashboard', compact('mainCategories', 'products', 'manufacturers', 'countries', 'subCategories', 'orders', 'archiveOrders', 'addresses', 'specialDealsPackages', 'featuredPackages', 'specialDealsProducts'));
    }

    // public function getOrderDetail($id)
    // {
    //     $order = Order::where('id', (int)$id)->where('customer_id', Auth::user()->customers->id)->with('customer', 'orderItems', 'orderItems.product', 'payment')->first();
    //     return $order;
    // }

    // public function view_wishlist()
    // {

    //     return view('front.wishlist', compact('products'));
    // }

    // public function add_wishlist(Request $request)
    // {
    //     $data = $request->all();
    //     $validator = Validator::make($data, [
    //         "product_id" => "required",
    //         "customer_id" => "required",
    //     ]);
    //     if ($validator->fails()) {
    //         return [
    //             "status" => false,
    //             "message" => "Unfortunately wishlist not added.",
    //             "errors" => $validator->messages()
    //         ];
    //     }
    //     if (isset($data['remove']) && $data['remove'] == 1) {
    //         CustomerWishlist::where('id', $data['wishlist_id'])->delete();
    //         return [
    //             "status" => true,
    //             "message" => "Wishlist removed Successfully!",
    //             "errors" => []
    //         ];
    //     }

    //     $data = CustomerWishlist::updateOrcreate([
    //         "product_id" => $data['product_id'],
    //         "customer_id" => $data['customer_id'],
    //     ], [
    //         "product_id" => $data['product_id'],
    //         "customer_id" => $data['customer_id'],
    //     ]);

    //     return [
    //         "status" => true,
    //         "message" => "Wishlist Added Successfully!",
    //         "errors" => [],
    //         "data" => $data
    //     ];
    // }


    public function updateAccountInformation(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'name' => 'required',
            'phone_number' => 'required',
            'zip_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if ($request->file('profile_picture')) {
            $file = $request->file('profile_picture');
            $new_image_name = date('YmdHis') . '-' . $file->getClientOriginalName();
            $file->move(public_path() . '/uploads/seller/', $new_image_name);
            $inputs['profile_picture'] = $new_image_name;
        }
        if ($request->file('cover_img')) {
            $fileC = $request->file('cover_img');
            $new_image_cover_name = date('YmdHis') . '-' . $fileC->getClientOriginalName();
            $fileC->move(public_path() . '/uploads/seller/', $new_image_cover_name);
            $inputs['cover_img'] = $new_image_cover_name;
        }

        unset($inputs['_token']);
        //        dd($inputs);
        \App\Models\Seller::where('user_id', Auth::user()->id)->update($inputs);

        return redirect('seller/dashboard')->with('success', 'Information Updated Successfully.');
    }


    public function updatePassword(Request $request)
    {
        $id = Auth::user()->id;

        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'current_password' => 'required',
            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
        ]);

        if ($validator->fails()) {
            return [
                "status" => false,
                "data" => [],
                "errors" => $validator->messages(),
                "message" => "Unexpected Error occured."
            ];
        }

        if (Hash::check($request->current_password, Auth::User()->password)) {
            $content = User::find($id);
            $content->password = Hash::make($request->password);
            if ($content->save()) {
                return [
                    "status" => true,
                    "data" => [],
                    "errors" => [],
                    "message" => "Successfully Updated"
                ];
            }
        } else {
            //                return back()->withErrors(['Sorry, your current Password not recognized. Please try again.']);
            return [
                "status" => false,
                "data" => [],
                "errors" => ['errors' => "Sorry, your current Password not recognized. Please try again."],
                "message" => "Sorry, your current Password not recognized. Please try again."
            ];
        }
    }

    //addSellerAddress
    public function addSellerAddress(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'add_phone_no' => 'required',
            'company_name' => 'required',
            'address1' => 'required',
            'country' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
        ]);

        $inputs['seller_id'] = Auth::user()->seller->id;
        $inputs['phone_no'] = $request->add_phone_no;

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $address = SellerAddress::where('seller_id', Auth::user()->seller->id)->count();
        if ($address > 0) {
            return redirect()->back()->with('error', "Address already Exist!");
        }

        SellerAddress::create($inputs);

        return redirect('/seller/dashboard')->with('success', 'Address Saved Successfully.');
    }

    //updateSellerAddress
    public function updateSellerAddress(Request $request)
    {
        $inputs = $request->all();
        //    dd($inputs);

        $validator = Validator::make($inputs, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone_no' => 'required',
            'address1' => 'required',
            'city' => 'required',
            'company_name' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'state' => 'required',
        ]);

        $inputs['seller_id'] = Auth::user()->seller->id;

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        unset($inputs['_token']);
        SellerAddress::where('id', $request->id)->where('seller_id', Auth::user()->seller->id)->update($inputs);

        return redirect('/seller/dashboard')->with('success', 'Address Updated Successfully.');
    }

    //getAddresses
    public function getAddresses(Request $request, $id)
    {
        $customer_address =  SellerAddress::where('id', $id)->where('seller_id', Auth::user()->seller->id)->first();
        $state = States::where('id', $customer_address->state)->first();
        $city = Cities::where('id', $customer_address->city)->first();
        return ['customer_address' => $customer_address, 'state' => $state, 'city' => $city];
    }
    //deleteAddress
    public function deleteAddress($id)
    {
        SellerAddress::find($id)->where('seller_id', Auth::user()->seller->id)->delete();
        session()->flash('success', 'Address Deleted Successfully!');
        return json_encode(array('statusCode' => 200));
    }
    //getCountries     
    public function getCountries()
    {
        $countries = Countries::select('id', 'name')->get();
        return $data = array('status' => 'success', 'tp' => 1, 'msg' => "Countries fetched successfully.", 'result' => $countries);
        //  return view('buyer.dashboard', compact('id', 'data'));
    }
    //getStates
    public function getStates($countryId)
    {
        $states = States::select('id', 'name')->where('country_id', $countryId)->get();
        $success['response_data'] = $states;
        return response()->json($success, 200);
        // $states = States::select('id','name')->where('country_id',$countryId)->get();
        // return $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Countries fetched successfully.", 'result'=>$states);
    }
    //getCities
    public function getCities($stateId)
    {
        $cities = Cities::select('id', 'name')->where('state_id', $stateId)->get();
        $success['response_data'] = $cities;
        return response()->json($success, 200);
        //  return $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Countries fetched successfully.", 'result'=>$cities);
    }

    public function searchSellerOrders(Request $request)
    {

        $orders = Order::query();

        if ($request->order_product !== null) {
            $keyword = $request->order_product;
            $orders = $orders->whereHas('orderItems.product', function ($q) use ($keyword) {
                $q->where('product_name', 'like', '%' . $keyword . '%')->where('seller_id', Auth::user()->seller->id);
            });
        }

        if ($request->from_date && $request->to_date) {
            $from = $request->from_date;

            $to = date('Y-m-d H:i:s', strtotime($request->to_date . ' +1 day'));
            $orders = $orders->whereBetween('created_at', [$from, $to]);
        }

        $orders = $orders->whereHas('orderItems', function ($q) {
            $q->where('seller_id', Auth::user()->seller->id)->whereNotNull('is_archive')->whereNull('deleted_at')->with(['product', 'product.category']);
        })
            ->with('buyer')
            ->orderBy('id', 'desc')
            ->get();

        // $archiveOrders = Order::whereHas('orderItems', function ($q) use ($seller_id) {
        //     $q->where('seller_id', $seller_id)->whereNotNull('is_archive')->whereNull('deleted_at')->with(['product', 'product.category']);
        // })
        //     ->with('buyer')
        //     ->orderBy('id', 'desc')
        //     ->get();

        return $orders;
    }

    public function archiveOrder(Request $request, $id)
    {

        // $order = Order::where('id', $id)->where('seller_id', Auth::user()->seller->id)->whereNull('is_archive')->whereNull('deleted_at')->first();
        $orders = OrderItem::where('order_id', $id)->where('seller_id', Auth::user()->seller->id)->whereNull('is_archive')->whereNull('deleted_at')->get();

        if (!empty($orders) && count($orders) > 0) {
            foreach ($orders as $order) {
                $order->update([
                    'is_archive' => $request->archive
                ]);
            }
            return 1;
        } else {
            return 0;
        }
    }

    public function changeOrderStatus(Request $request, $id)
    {

        $order = Order::where('id', $id)->where('seller_id', Auth::user()->seller->id)->whereNull('deleted_at')->first();

        if (!empty($order)) {
            $order->update(['order_status' => $request->val]);
            return 1;
        } else {
            return 0;
        }
    }



    public function getOrderDetails(Request $request, $id)
    {
        $seller_id = Auth::user()->seller->id;
        // $order = Order::where('id', $id)->where('seller_id', Auth::user()->seller->id)->whereNull('deleted_at')->with('orderItems', 'buyer', 'orderItems.product')->first();
        $order = Order::where('id', $id)->with(['orderItems' => function ($q) use ($seller_id) {
            $q->where('seller_id', $seller_id)->with(['product', 'product.category', 'shippingLabels']);
        }])
            ->with('buyer')
            ->orderBy('id', 'desc')
            ->first();

        if (!empty($order)) {
            $html = view('seller.partials.orderDetails', compact('order'))->render();
            return response()->json(['html' => $html]);
        } else {
            return 0;
        }
    }


    public function sellerDeleteOrder($id)
    {

        // $order = Order::where('id', $id)->where('seller_id', Auth::user()->seller->id)->whereNull('deleted_at')->first();
        $orders = OrderItem::where('order_id', $id)->where('seller_id', Auth::user()->seller->id)->whereNull('deleted_at')->get();

        $todaysDate = date('Y-m-d');
        if (!empty($orders) && count($orders) > 0) {
            foreach ($orders as $order) {
                $order->update(['deleted_at' => $todaysDate]);
            }
            return 1;
        } else {
            return 0;
        }
    }


    public function markProductSpecialDeal(Request $request)
    {

        $seller_id = auth()->user()->seller->id;

        $product = Product::where(['id' => (int)$request->product_id, 'seller_id' => $seller_id])->first();
        $deal = SpecialDealsPackage::where('id', (int)$request->deal_id)->where('amount', (float)$request->amount)->first();

        if ($deal !== null) {
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'errorMsg' => 'Invalid Product',
                ]);
            } else {

                $todaysDate = date('Y-m-d');
                $endDate =  date('Y-m-d', strtotime($todaysDate . ' + ' . (int)$deal->days . ' days'));

                $specialDealsProduct = SpecialDealsProducts::create([
                    'special_deals_id' => $deal->id,
                    'seller_id' => $seller_id,
                    'product_id' => (int)$request->product_id,
                    'amount' => $request->amount,
                    'start_date' => $todaysDate,
                    'end_date' => $endDate,
                    'status' => 1,
                ]);

                Payment::create([
                    'seller_id' => $seller_id,
                    'special_deal_product_id' =>
                    (int)$deal->id,
                    'amount' => (float)$request->amount,
                    'description' => 'Product for Special Deals',
                    'pay_method_name' => 'Paypal',
                    'featured_plan_id' => (int)$request->featured_id,
                    'featured_start_date' => $todaysDate,
                    'featured_end_date' => $endDate
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Product Submited for Featured'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'errorMsg' => 'Something went wrong',
            ]);
        }
    }

    public function markAsFeatured(Request $request)
    {
        // dd($request->all());
        $seller_id = auth()->user()->seller->id;

        $feat = FeaturedPackage::where('id', (int)$request->feat_id)->where('amount', (float)$request->amount)->first();

        if ($feat !== null) {

            if ($request->file('banner')) {
                $file = $request->file('banner');
                $new_image_name = date('YmdHis') . '-' . $file->getClientOriginalName();
                $file->move(public_path() . '/uploads/featured_ads/', $new_image_name);
                $banner = $new_image_name;

                $todaysDate = date('Y-m-d');
                $endDate =  date('Y-m-d', strtotime($todaysDate . ' + ' . (int)$feat->days . ' days'));

                $featuredAdId = FeaturedAd::create([
                    'featured_package_id' => $feat->id,
                    'seller_id' => $seller_id,
                    'banner' => $banner,
                    'amount' => $request->amount,
                    'start_date' => $todaysDate,
                    'end_date' => $endDate,
                    'status' => 1,
                ]);

                Payment::create([
                    'seller_id' => $seller_id,
                    'featured_ad_id' =>
                    (int)$featuredAdId->id,
                    'amount' => (float)$request->amount,
                    'description' => 'Featured Ad',
                    'pay_method_name' => 'Paypal',
                    'featured_start_date' => $todaysDate,
                    'featured_end_date' => $endDate
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Banner Submited for Featured'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'errorMsg' => 'Something went wrong',
            ]);
        }
    }


    public function editOrderTrackingNo($id)
    {
        // try {
        $seller_id = Auth::user()->seller->id;
        $order = Order::where('id', $id)->with(['orderItems' => function ($q) use ($seller_id) {
            $q->where('seller_id', $seller_id)->with(['product', 'product.category', 'shippingLabels']);
        }])
            ->with('buyer')
            ->orderBy('id', 'desc')
            ->first();

        if (!empty($order)) {
            $html = view('seller.partials.addOrderTrackingNo', compact('order'))->render();
            return response()->json([
                'status' => true,
                'html' => $html,
                'msg' => "Order Details"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'html' => "",
                'msg' => "Order Not Found!"
            ]);
        }
        // } catch (\Exception $ex) {
        //     // return redirect()->back()->with('error', $ex->getMessage());
        //     return response()->json([
        //         'status' => false,
        //         'html' => "",
        //         'msg' => $ex->getMessage(),
        //     ]);
        // }
    }

    public function updateOrderTrackingNo(Request $request)
    {

        try {

            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                "tracking_no"    => "required",
                "orderItemId"    => "required",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'msg' => $validator->messages()
                ]);
            }

            if ($request->orderItemId !== "") {

                foreach ($request->tracking_no as $tracking_no) {
                    $id = explode('=', $tracking_no);

                    if ((int)$id[1] !== 0) {

                        OrderShippingLabel::where('id', $id[1])->update([
                            'tracking_number' => $id[0],
                        ]);
                    } else {

                        OrderShippingLabel::create([
                            'order_item_id' => $request->orderItemId,
                            'tracking_number' => $id[0],
                        ]);
                    }
                }
            }

            return response()->json([
                'status' => true,
                'msg' => 'Tracking No Updated Successfully'
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'msg' => $ex->getMessage()
            ]);
        }
    }
}
