<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\Cities;
use App\Models\Countries;
use App\Models\CustomerAddress;
use App\Models\Customers;
use App\Models\CustomerWishlist;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\States;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use MongoDB\Driver\Session;
use Stripe\Customer;
use Validator;

class BuyerController extends Controller
{
    public function dashboard()
    {

        $whishlist_products = CustomerWishlist::where('buyer_id', Auth::user()->buyer->id)->with('product')->orderBy('id', 'desc')->get();

        $addresses = CustomerAddress::where('customer_id', Auth::user()->buyer->id)->with('countryName', 'cityName', 'stateName')->get();
        $countries = Countries::all();
        $states = States::all();

        $orders = Order::with('orderItems', 'seller', 'orderItems.product', 'orderItems.product.category')->where('buyer_id', Auth::user()->buyer->id)->whereNull('deleted_at')->orderBy('id', 'desc')->get();

        return view('buyer.dashboard', compact(['countries', 'states', 'addresses', 'whishlist_products', 'orders']));
    }

    public function getOrderDetail($id)
    {
        $order = Order::where('id', (int)$id)->where('customer_id', Auth::user()->buyer->id)->with('customer', 'orderItems', 'orderItems.product', 'payment')->first();
        return $order;
    }

    public function view_wishlist()
    {
        return view('front.wishlist', compact('products'));
    }


    // add products to wish list

    public function add_wishlist(Request $request)
    {

        $data = $request->all();
        $validator = Validator::make(
            $data,
            [
                "product_id" => "required",
                "buyer_id" => "required",
            ]
        );


        if ($validator->fails()) {
            return [
                "status" => false,
                "message" => "Unfortunately Favorite not added.",
                "errors" => $validator->messages()
            ];
        }

        if (isset($data['remove']) && $data['remove'] == 1) {

            CustomerWishlist::where('id', $data['wishlist_id'])->delete();
            return [
                "status" => true,
                "message" => "Favorite removed Successfully!",
                "errors" => []
            ];
        }

        $data = CustomerWishlist::updateOrcreate([
            "product_id" => $data['product_id'],
            "buyer_id" => $data['buyer_id'],
        ], [
            "product_id" => $data['product_id'],
            "buyer_id" => $data['buyer_id'],
        ]);

        return [
            "status" => true,
            "message" => "Favorite Added Successfully!",
            "errors" => [],
            "data" => $data
        ];
    }

    //end function add products to wish list

    public function updateAccountInformation(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'name' => 'required',
            'phone_number' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        // dd(Buyer::where('user_id', Auth::user()->id)->get());

        Buyer::where('user_id', Auth::user()->id)->update([
            'name' => $inputs['name'],
            'phone_number' => $inputs['phone_number'],
            'zip_code' => $inputs['zip_code']
        ]);
        User::where('id', Auth::user()->id)->update([
            'name' => $inputs['name'],

        ]);

        return redirect('buyer/dashboard')->with('success', 'Information Updated Successfully.');
    }


    public function addCustomerAddress(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'add_phone_no' => 'required',
            // 'company_name' => 'required',
            'address1' => 'required',
            'country' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
        ]);

        $inputs['customer_id'] = Auth::user()->buyer->id;
        $inputs['phone_no'] = $request->add_phone_no;

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $address = CustomerAddress::where('customer_id', Auth::user()->buyer->id)->count();
        if ($address > 0) {
            return redirect()->back()->with('error', "Address already Exist!");
        }

        CustomerAddress::create($inputs);

        return redirect('/buyer/dashboard')->with('success', 'Address Saved Successfully.');
    }

    //update buyer password
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

    //end update buyer password



    public function getAddressDetail(Request $request, $id)
    {
        $customer_address =  CustomerAddress::where('id', $id)->where('customer_id', Auth::user()->buyer->id)->first();
        $state = States::where('id', $customer_address->state)->first();
        $city = Cities::where('id', $customer_address->city)->first();
        return ['customer_address' => $customer_address, 'state' => $state, 'city' => $city];
    }


    public function updateCustomerAddress(Request $request)
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
            // 'company_name' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'state' => 'required',
        ]);

        $inputs['customer_id'] = Auth::user()->buyer->id;

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        unset($inputs['_token']);
        CustomerAddress::where('id', $request->id)->where('customer_id', Auth::user()->buyer->id)->update($inputs);

        return redirect('/user/dashboard')->with('success', 'Address Updated Successfully.');
    }


    public function getCountries()
    {
        $countries = Countries::select('id', 'name')->get();
        return $data = array('status' => 'success', 'tp' => 1, 'msg' => "Countries fetched successfully.", 'result' => $countries);
        //  return view('buyer.dashboard', compact('id', 'data'));
    }

    public function getStates($countryId)
    {
        $states = States::select('id', 'name')->where('country_id', $countryId)->get();
        $success['response_data'] = $states;
        return response()->json($success, 200);

        // $states = States::select('id','name')->where('country_id',$countryId)->get();
        // return $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Countries fetched successfully.", 'result'=>$states);
    }

    public function getCities($stateId)
    {

        $cities = Cities::select('id', 'name')->where('state_id', $stateId)->get();
        $success['response_data'] = $cities;
        return response()->json($success, 200);

        //  return $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Countries fetched successfully.", 'result'=>$cities);

    }

    // get addresses
    public function getAddresses(Request $request, $id)
    {
        $customer_address =  CustomerAddress::where('id', $id)->where('customer_id', Auth::user()->buyer->id)->first();
        $state = States::where('id', $customer_address->state)->first();
        $city = Cities::where('id', $customer_address->city)->first();
        return ['customer_address' => $customer_address, 'state' => $state, 'city' => $city];
    }

    public function deleteAddress($id)
    {

        CustomerAddress::find($id)->where('customer_id', Auth::user()->buyer->id)->delete();
        session()->flash('success', 'Address Deleted Successfully!');
        return json_encode(array('statusCode' => 200));
    }


    public function searchWishlistProduct(Request $request)
    {
        $value = $request->get('keyword');
        return $products = CustomerWishlist::where('buyer_id', Auth::user()->buyer->id)->whereHas('product', function ($query) use ($value) {
            return $query->where('product_name', 'like', '%' . $value . '%')->where('product_name', '<>', null);
        })->with(['product', 'product.category', 'product.countryName', 'product.whishlist'])->get();
    }

    public function searchBuyerOrders(Request $request)
    {
        $orders = Order::query();

        if ($request->order_product !== null) {
            $keyword = $request->order_product;
            $orders = $orders->whereHas('orderItems.product', function ($q) use ($keyword) {
                $q->where('product_name', $keyword);
            });
        }

        if ($request->from_date && $request->to_date) {
            $from = $request->from_date;
            $to = $request->to_date;
            $orders = $orders->whereBetween('created_at', [$from, $to]);
        }

        $orders = $orders->with(['orderItems', 'seller', 'orderItems.product', 'orderItems.product.category'])->where('buyer_id', Auth::user()->buyer->id)->whereNull('deleted_at')->get();
        return $orders;
    }

    public function getOrderDetails(Request $request, $id)
    {

        $order = Order::where('id', $id)->where('buyer_id', Auth::user()->buyer->id)->whereNull('deleted_at')->with('orderItems', 'orderItems.seller', 'orderItems.product', 'orderItems.shippingLabels')->first();

        if (!empty($order)) {
            $html = view('buyer.partials.orderDetails', compact('order'))->render();
            return response()->json(['html' => $html]);
        } else {
            return 0;
        }
    }

    public function markOrderStatus(Request $request)
    {

        try {
            $order = Order::where('id', $request->order_id)->where('buyer_id', Auth::user()->buyer->id)->first();

            if (!empty($order)) {
                $orderItem = OrderItem::where('id', $request->id)->first();
                if (!empty($orderItem)) {
                    $orderItem->status = 2;
                    $orderItem->save();
                    return response()->json(['status' => true, 'msg' => "Status Updated Successfully"]);
                } else {
                    return response()->json(['status' => false, 'msg' => "Order not found!"]);
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['status' => false, 'msg' => $ex->getMessage()]);
        }
    }
}
