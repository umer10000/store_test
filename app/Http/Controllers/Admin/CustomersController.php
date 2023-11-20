<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Buyer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Settings;
use App\User;
use Validator;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    final public function index()
    {
        try {
            if (request()->ajax()) {

                return datatables()->of(Buyer::where('deleted_at', null)->with('user')->get())
                    ->addColumn('email', function ($data) {
                        return $data->user->email;
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="customers/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;<a title="edit" href="buyers/' . $data->id . '/edit" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['email', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.customers.index');
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
        //
        try {
            $content = Buyer::where('id', $id)->with('user', 'buyerAddress')->first();
            $orders = Order::where('buyer_id', $id)->with('orderItems', 'orderItems.product')->orderBy('id', 'desc')->get();

            return view('admin.customers.show', compact('content', 'orders'));
        } catch (\Exception $ex) {
            return redirect('admin/buyers')->with('error', $ex->getMessage());
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
            $buyer = Buyer::where('id', $id)->with('user')->first();

            return view('admin.customers.edit', compact('buyer'));
        } catch (\Exception $ex) {
            return redirect('admin/buyers')->with('error', $ex->getMessage());
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
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'buyer_name' => 'required',
            'phone' => 'required',
            'zip_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        try {
            $buyer = Buyer::where('id', $id)->first();

            $buyer->name = $inputs['buyer_name'];
            $buyer->phone_number = $inputs['phone'];
            $buyer->zip_code = $inputs['zip_code'];
            $buyer->save();

            User::where('id', $buyer->user_id)->update([
                'name' => $inputs['buyer_name'],

            ]);
            return redirect('admin/buyers')->with('success', 'Buyer Updated Successfully.');
        } catch (\Exception $ex) {
            return redirect('admin/buyers')->with('error', $ex->getMessage());
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
        $buyer = Buyer::where('id', $id);

        if ($buyer->count() > 0) {
            $buyer->update(['deleted_at' => date('Y-m-d')]);

            //Mail Process
            $buyerdata = Buyer::where('id', $id)->with('user')->first();
            //User Update
            $userid = $buyerdata->user->id;
            $user = User::where('id', $userid);
            $user->update(['status' => 0]);

            $buyer_name = $buyerdata->user->name;
            $buyer_email = $buyerdata->user->email;
            $setting = Settings::find(1);
            $mailData = array(
                'name' => $buyer_name,
                'email' => $setting->email,
                'userMessage' => 'Your Buyer Account has been Inactive',
                'to' => $buyer_email,
            );

            Mail::send('admin.emails.buyer-account-email', $mailData, function ($message) use ($mailData) {
                $message->to($mailData['to'])->from($mailData['email'])
                    ->subject('Your Account Has been Inactive');
            });

            return 1;
        }
        // $check = Order::where('buyer_id', $id)->count(); //buyerid=orders->buyerid
        // if ($check > 0) {
        //     echo 0;
        //     return;
        // }
        // $content = Buyer::find($id);
        // if (!empty($content)) {
        //     $content->delete();
        //     echo 1;
        // } else {
        //     echo 2;
        // }
    }
}

 



        // $content=Buyer::find($id);
        // if(!empty($content) ){
        //     $content->delete();
        //     echo 1;
        // }else{echo 2;}



// $check = Product::where('seller_id',$id)->count(); //seller -> product 

// if($check > 0){
//     echo 0;
//     return;
// }

// $content=Product::find($id);

// $content->delete();//
// echo 1;
// $content=Seller::find($id);

// if(!empty($content) ){
//     $content->delete();
//     echo 1;
// }else{echo 2;}
// }