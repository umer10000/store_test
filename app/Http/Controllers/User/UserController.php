<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Cities;
use App\Models\Countries;
use App\Models\CustomerAddress;
use App\Models\Customers;
use App\Models\CustomerWishlist;
use App\Models\Order;
use App\Models\States;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    public function dashboard()
    {
        $orders = Order::where('customer_id',Auth::user()->customers->id)->get();

        $recentOrders = Order::where('customer_id',Auth::user()->customers->id)->orderBy('id','desc')->take(10)->get();

        $products = CustomerWishlist::where('customer_id',Auth::user()->customers->id)->with('product')->get();
        $addresses = CustomerAddress::where('customer_id',Auth::user()->customers->id)->get();

        $countries = Countries::all();

        return view('user.dashboard',compact('orders','products','recentOrders','addresses','countries'));
    }

    public function getOrderDetail($id)
    {
        $order = Order::where('id',(int)$id)->where('customer_id',Auth::user()->customers->id)->with('customer','orderItems','orderItems.product','payment')->first();
        return $order;
    }

    public function view_wishlist()
    {

        // dd($products);
        return view('front.wishlist', compact('products'));
    }

    public function add_wishlist(Request $request){
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
        if(isset($data['remove']) && $data['remove'] == 1){
            CustomerWishlist::where('id', $data['wishlist_id'])->delete();
            return [
                "status" => true,
                "message" => "Wishlist removed Successfully!",
                "errors" => []
            ];
        }

        $data = CustomerWishlist::updateOrcreate([
            "product_id" => $data['product_id'],
            "customer_id" => $data['customer_id'],
        ],[
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

    public function updateAccountInformation(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs,[
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_no' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        Customers::where('user_id',Auth::user()->id)->update([
            'first_name' => $inputs['first_name'],
            'last_name' => $inputs['last_name'],
            'phone_no' => $inputs['phone_no'],
            'gender' => $inputs['gender'],
            'country_code' => $inputs['country_code'],
            'notification_check' => (isset($inputs['notification_check'])) ? $inputs['notification_check'] : 0,
        ]);

        if($request->change_pass_check == 'yes'){
            $this->validate($request, [
                'old_password' => 'required',
                'new_password' => 'min:8|required_with:password_confirmation|same:confirm_password',
            ]);

            if (Hash::check($inputs['old_password'], Auth::User()->password)) {
                $id = Auth::user()->id;
                $content = User::find($id);
                $content->password = Hash::make($inputs['password']);
                if ($content->save()) {
                    return redirect('/user/dashboard')->with('success', 'Information Updated Successfully.');
                }
            } else {
                return redirect()->back()->withErrors(['Sorry, your current Password not recognized. Please try again.']);
            }
        }else{
            return redirect('/user/dashboard')->with('success', 'Information Updated Successfully.');
        }
    }

    // add customers address 

    public function addCustomerAddress(Request $request)
    {
        $inputs = $request->all();
//        dd($inputs);

        $validator = Validator::make($inputs,[
            'first_name' => 'required',
            'last_name' => 'required',
            'add_phone_no' => 'required',
            'phone_no_code' => 'required',
            'title' => 'required',
            'address' => 'required',
            'city' => 'required',
            'company_name' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'state' => 'required',
        ]);

        $inputs['customer_id'] = Auth::user()->customers->id;
        $inputs['phone_no'] = $request->add_phone_no;

        if($validator->fails()){
            return redirect()->back()->with('error','Please Fill all Fields');
        }


        CustomerAddress::updateOrCreate([
            'customer_id' => Auth::user()->customers->id,
            'shipping_billing' => $request->shipping_billing
        ],$inputs);

        return redirect('/user/dashboard')->with('success', 'Address Saved Successfully.');

    }

    //end add customers address 
    

    public function getAddressDetail(Request $request,$id)
    {
        $customer_address =  CustomerAddress::where('id',$id)->where('customer_id',Auth::user()->customers->id)->first();

        $state = States::where('id',$customer_address->state)->first();
        $city = Cities::where('id',$customer_address->city)->first();
        return ['customer_address' => $customer_address,'state' => $state,'city' => $city];
    }


    public function updateCustomerAddress(Request $request)
    {
        $inputs = $request->all();
//        dd($inputs);

        $validator = Validator::make($inputs,[
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_no' => 'required',
            'title' => 'required',
            'address' => 'required',
            'city' => 'required',
            'company_name' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'state' => 'required',
            'shipping_billing_e' => 'required',
        ]);

        $inputs['customer_id'] = Auth::user()->customers->id;
        $inputs['phone_no_code'] = $request->phone_no_code_e;

        if($validator->fails()){
            return redirect()->back()->with('error','Please Fill all Fields');
        }


        CustomerAddress::updateOrCreate([
            'customer_id' => Auth::user()->customers->id,
            'id' => $request->id,
            'shipping_billing' => $request->shipping_billing_e
        ],$inputs);

        return redirect('/user/dashboard')->with('success', 'Address Saved Successfully.');

    }


    public function getCountries()
    {
        $countries = Countries::select('id','name')->get();
        return $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Countries fetched successfully.", 'result'=>$countries);
    }

    public function getStates($countryId)
    {
        $states = States::select('id','name')->where('country_id',$countryId)->get();
        return $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Countries fetched successfully.", 'result'=>$states);
    }

    public function getCities($stateId)
    {
        $cities = Cities::select('id','name')->where('state_id',$stateId)->get();
        return $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Countries fetched successfully.", 'result'=>$cities);
    }





}
