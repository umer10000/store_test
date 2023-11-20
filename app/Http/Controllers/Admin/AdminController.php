<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\Cities;
use App\Models\Countries;
use App\Models\Customers;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Seller;
use App\Models\States;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data['orders'] = Order::where('order_status', 'pending')->count();
        $data['products'] = Product::count();
        $data['buyers'] = Buyer::count();
        $data['sellers'] = Seller::count();

        return view('admin.dashboard', compact('data'));
    }


    public function payments()
    {
        try {
            if (request()->ajax()) {
                return datatables()->of(Payment::where('deleted_at',Null)->orderBy('id', 'desc')->get())
                    ->addColumn('checkbox', function ($data) {
                        return '<input type="checkbox" name="order_id[]" value="' . $data->id . '" class="order_id"/>';
                    })
                    ->addColumn('description', function ($data) {
                        if ($data->description == null) {
                            return "Order";
                        } else {
                            return $data->description;
                        }
                    })
                    ->addColumn('amount', function ($data) {
                        return presentPrice($data->amount);
                    })
                    ->addColumn('created_at', function ($data) {
                        return date('d-m-Y', strtotime($data->created_at));
                    })->addIndexColumn()
                    ->rawColumns(['checkbox','description'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.payments.index');
    }


    //getCountries     
    public function getCountries()
    {
        $countries = Countries::select('id', 'name')->get();
        return $data = array('status' => 'success', 'tp' => 1, 'msg' => "Countries fetched successfully.", 'result' => $countries);
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

    public function deleteBulkPayment(Request $request)
    {
        // dd('dsadas');
        try {
            $payments = Payment::whereIn('id', $request->id_array);

            if ($payments->count() > 0) {
                foreach ($payments->get() as $payment) {
                    $payment->deleted_at = date('Y-m-d');
                    $payment->save();
                }
                return 1;
            } else {
                return 0;
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

}
