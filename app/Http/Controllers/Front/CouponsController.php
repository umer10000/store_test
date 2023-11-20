<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponsController extends Controller
{
    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->coupon_code)
            ->whereStatus(1)->first();
        $json = array();
        $userId = null;
        if (Auth::check()){
            $userId = Auth::id();
        }

        if (!$coupon) {
            $json['error'] = "Invalid coupon code. Please try again.";
        }else{
            /*  In case of specific customers */
            if($coupon->customer_id != null){
                $customers = explode(',' , $coupon->customer_id);
                if (!in_array($userId, $customers)){
                    $json['error'] = 'Invalid coupon code. Please try again.';
                }

            }

            if($coupon->expiration_date != null && $coupon->expiration_date <= date('Y-m-d')){
                $json['error'] = 'Your Coupon is Expired';
            }

            /*  Usage: In case of specific customers */
            if ($coupon->customer_id != null && $coupon->usage == 0){
                $json['error'] = 'Your Coupon is Expired';
            }
            if ($coupon->usage === $coupon->used){
                $json['error'] = 'Your Coupon Limit Exceeded';
            }
        }


        if (!empty($json['error'])){
            $json['status'] = false;
        }
        else{
            $json['status'] = true;
            $json['success'] = 'Coupon has been applied!';
            $json['data'] = $coupon;
        }

        return $json;


        //return back()->with('success_message', 'Coupon has been applied!');
    }
}
