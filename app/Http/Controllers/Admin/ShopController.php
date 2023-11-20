<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cities;
use App\Models\Countries;
use App\Models\Seller;
use App\Models\States;
use App\SellerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Count;
use Validator;

class ShopController extends Controller
{
    public function adminShop()
    {
        $shop = Seller::where('admin_shop', 1)->with('user')->first();

        return view('admin.shop.shop-setup', compact('shop'));
    }

    public function updateAdminShop(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($request->all(), array(
            'name' => 'required',
            'phone_number' => 'required|numeric',
            'zip_code' => 'required|numeric'

        ));

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
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
            Seller::where('admin_shop', 1)->update($inputs);

            return redirect()->back()->with('success', 'Shop Updated Successfully');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function adminShopAddress()
    {
        try {
            $shop = SellerAddress::where('seller_id', Auth::user()->seller->id)->with('seller.user')->first();
            $countries = Countries::all();
            $states = States::where('country_id', $shop->country)->get();
            $cities = Cities::where('state_id', $shop->state)->get();
            return view('admin.shop.shop-address', compact('countries', 'shop', 'states', 'cities'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function updateAdminShopAddress(Request $request)
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

        try {
            unset($inputs['_token']);
            SellerAddress::updateOrCreate([
                'seller_id' => Auth::user()->seller->id
            ], [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_no' => $request->phone_no,
                'address1' => $request->address1,
                'city' => $request->city,
                'company_name' => $request->company_name,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
                'state' => $request->state,
            ]);

            return redirect()->back()->with('success', 'Address Updated Successfully.');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }
}
