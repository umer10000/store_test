<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Mail;
use App\Models\Settings;
use App\User;
use Validator;

class SellersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if (request()->ajax()) {
                return datatables()->of(Seller::where('deleted_at', null)->where('admin_shop', 0)->with('user')->get())
                    ->addColumn('email', function ($data) {
                        return $data->user->email;
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="sellers/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;<a title="edit" href="sellers/' . $data->id . '/edit" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['email', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.sellers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        try {

            $content = Seller::where('id', $id)->with('user', 'products')->first();
            $orders = Order::whereHas('orderItems', function ($q) use ($id) {
                $q->where('seller_id', $id)->with(['product', 'product.category']);
            })
                ->with('buyer')
                ->orderBy('id', 'desc')
                ->get();

            return view('admin.sellers.show', compact('content', 'orders'));
        } catch (\Exception $ex) {
            return redirect('admin/sellers')->with('error', $ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $seller = Seller::where('id', $id)->with('user', 'products')->first();
            return view('admin.sellers.edit', compact('seller'));
        } catch (\Exception $ex) {
            return redirect('admin/sellers')->with('error', $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $inputs = $request->all();
            $validator = Validator::make($inputs, [
                'seller_name' => 'required',
                'phone' => 'required',
                'zip_code' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            unset($inputs['_token']);

            $seller = Seller::where('id', $id)->first();
            $seller->name = $inputs['seller_name'];
            $seller->phone_number = $inputs['phone'];
            $seller->zip_code = $inputs['zip_code'];
            $seller->about = $inputs['about'];
            $seller->save();

            User::where('id', $seller->user_id)->update([
                "name" => $inputs['seller_name']
            ]);

            return redirect('admin/sellers')->with('success', 'Seller Updated Successfully.');
        } catch (\Exception $ex) {
            return redirect('admin/sellers')->with('error', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $seller = Seller::where('id', $id);

        if ($seller->count() > 0) {
            $seller->update(['deleted_at' => date('Y-m-d')]);

            //Mail Process
            $sellerdata = Seller::where('id', $id)->with('user')->first();
            //User Update
            $userid = $sellerdata->user->id;
            $user = User::where('id', $userid);
            $user->update(['status' => 0]);

            $seller_name = $sellerdata->user->name;
            $seller_email = $sellerdata->user->email;
            $setting = Settings::find(1);
            $mailData = array(
                'name' => $seller_name,
                'email' => $setting->email,
                'userMessage' => 'Your Seller Account has been Inactive By Admin.For any Query you can submit Contact us form on Website Home Page',
                'to' => $seller_email,
            );

            Mail::send('admin.emails.seller-account-email', $mailData, function ($message) use ($mailData) {
                $message->to($mailData['to'])->from($mailData['email'])
                    ->subject('Your Account Has been Inactive');
            });

            return 1;
        }
        // $check = Product::where('seller_id', $id)->count(); //seller -> product 

        // if ($check > 0) {
        //     echo 0;
        //     return;
        // }
        // $content = Product::find($id);
        // $content->delete(); //
        // echo 1;
        // $content = Seller::find($id);

        // if (!empty($content)) {
        //     $content->delete();
        //     echo 1;
        // } else {
        //     echo 2;
        // }
    }
}
