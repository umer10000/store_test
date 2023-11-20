<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    public function index()
    {

        try {
            if (request()->ajax()) {
                return datatables()->of(ShippingRate::get())
                    ->addColumn('status', function ($data){
                        if($data->status == 0){
                            return '<label class="switch"><input type="checkbox"  data-id="'.$data->id.'" data-val="1"  id="status-switch"><span class="slider round"></span></label>';
                        }else{
                            return '<label class="switch"><input type="checkbox" checked data-id="'.$data->id.'" data-val="0"  id="status-switch"><span class="slider round"></span></label>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="shipping/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;<a title="Edit" href="shipping/edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['status','action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.shipping.index');
    }

    public function create(Request $request){
        try{
            if($request->method() == 'POST'){
                $this->validate($request, array(
                    'rate' => 'required'
                ));

                ShippingRate::create([
                    'rate' => $request->input('rate'),
                    'status' => $request->input('status') ? 1 : 0
                ]);
                return redirect(route('shipping.index'))->with('success', 'shipping Added');
            }
        }catch (\Exception $ex){
            return redirect(route('shipping.index'))->with('error', $ex->getMessage());
        }
        return view('admin.shipping.create');
    }

    public function show($id)
    {
        $shippingRate = ShippingRate::where('id',$id)->first();
        if (empty($shippingRate)){
            abort(404);
        }
        return view('admin.shipping.show',compact('shippingRate'));
    }

    public function edit(Request $request, $id){
        $shippingRate = ShippingRate::where('id',$id)->first();
        if(empty($shippingRate)){
            abort(404);
        }
        if($request->method() == 'POST'){
            try{
                $this->validate($request, array(
                    'rate' => 'required',
                ));

                $shippingRate->rate = $request->input('rate');
                $shippingRate->status = $request->input('status') ? 1 : 0;
                $shippingRate->save();

                return redirect(route('shipping.index'))->with('success', 'Shipping Updated');
            }catch(\Exception $ex){
                return redirect(route('shipping.index'))->with('error', $ex->getMessage());
            }

        }
        return view('admin.shipping.edit',compact('shippingRate'));
    }

    public function destroy($id)
    {
        $content = ShippingRate::find($id);
        $content->delete();
        echo 1;

    }

    public function changeStatus(Request $request, $id){
        $shippingRate = ShippingRate::where('id',$id)->first();
        if(empty($shippingRate)){
            return 0;
        }
        $status = $shippingRate->status;
        if($status == 0){
            $status = 1;
        }else{
            $status = 0;
        }
        if($request->method() == 'POST'){
            $shippingRate->status = $status;
            $shippingRate->save();
        }
        return 1;
    }










}
