<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function index(Request $request){
        try {
            if (request()->ajax()) {
                return datatables()->of(Coupon::get())
                    ->addColumn('status', function ($data){
                        if($data->status == 0){
                            return '<label class="switch"><input type="checkbox"  data-id="'.$data->id.'" data-val="1"  id="status-switch"><span class="slider round"></span></label>';
                        }else{
                            return '<label class="switch"><input type="checkbox" checked data-id="'.$data->id.'" data-val="0"  id="status-switch"><span class="slider round"></span></label>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="coupon/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;<a title="Edit" href="coupon/edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['status','action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.coupon.index');
    }

    public function show(Request $request, $id){
        $coupon = Coupon::findOrFail($id);
        if (empty($coupon)){
            abort(404);
        }
        return view('admin.coupon.show',compact('coupon'));
    }

    public function create(Request $request){

        if ($request->method() == 'POST'){
            $validator = Validator::make($request->all(), [
                'code' => 'required|string',
                'value' => 'required|numeric',
                'type' => 'required|string',
            ]);

            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            if ($request->has('customers')){
                $customerIds = implode(',', $request->input('customers'));
            }

            Coupon::create([
                'code' => $request->input('code'),
                'value' => $request->input('value'),
                'customer_id' => isset($customerIds) ? $customerIds : null,
                'type' => $request->input('type') ?? 'value',
                'expiration_date' => $request->input('expiration_date') ?? null,
                'usage' => $request->input('usage') ?? 1,
                'status' => 1,
            ]);
            return redirect(route('coupons.index'))->with('success', 'Coupon Added');
        }
        $customers = Customers::where('status', 1)->get();
        return view('admin.coupon.create', compact('customers'));
    }

    public function edit(Request $request, $id){
        $coupon = Coupon::findOrFail($id);
        if (empty($coupon)){
            abort(404);
        }

        if ($request->method() == 'POST'){
            $validator = Validator::make($request->all(), [
                'code' => 'required|string',
                'value' => 'required|numeric',
                'type' => 'required|string',
            ]);

            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            if ($request->has('customers')){
                $customerIds = implode(',', $request->input('customers'));
            }

            $coupon->code = $request->input('code');
            $coupon->value = $request->input('value');
            $coupon->customer_id = isset($customerIds) ? $customerIds : null;
            $coupon->type = $request->input('type') ?? 'value';
            $coupon->expiration_date = $request->input('expiration_date') ?? null;
            $coupon->usage = $request->input('usage') ?? 1;
            $coupon->status = $request->input('status') ? 1 : 0;
            $coupon->save();
            return redirect(route('coupons.index'))->with('success', 'Coupon Updated');
        }

        $customers = Customers::where('status', 1)->get();
        return view('admin.coupon.edit',compact('coupon', 'customers'));
    }

    public function destroy($id){
        $content = Coupon::find($id);
        $content->delete();
        echo 1;
    }

    public function changeStatus(Request $request, $id){
        $coupon = Coupon::find($id);
        if(empty($coupon)){
            return 0;
        }
        $status = $coupon->status;
        if($status == 0){
            $status = 1;
        }else{
            $status = 0;
        }
        if($request->method() == 'POST'){
            $coupon->status = $status;
            $coupon->save();
        }
        return 1;
    }
}
