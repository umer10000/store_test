<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Countries;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderShippingLabel;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class OrderController extends Controller
{
    public function index()
    {

        try {
            if (request()->ajax()) {
                return datatables()->of(Order::with('buyer', 'country_name', 'state_name', 'city_name')->whereNull('deleted_at')->orderBy('id', 'desc')->get())
                    ->addColumn('order_no', function ($data) {
                        return $data->order_no ?? '';
                    })->addColumn('buyer', function ($data) {
                        if ($data->buyer_id == null) {
                            return $data->buyer_name;
                        } else {
                            return $data->buyer->name;
                        }
                    })->addColumn('total_amount', function ($data) {
                        return '$' . number_format($data->total_amount, 2) ?? '';
                    })->addColumn('checkbox', function ($data) {
                        return '<input type="checkbox" name="order_id[]" value="' . $data->id . '" class="order_id"/>';
                    })->addColumn('order_date', function ($data) {
                        return date('d-M-Y', strtotime($data->created_at)) ?? '';
                    })->addColumn('status', function ($data) {
                        if ($data->order_status == 'pending') {
                            return '<span class="badge badge-secondary">Pending</span>';
                        } elseif ($data->order_status == 'paid') {
                            return '<span class="badge badge-primary">Paid</span>';
                        } elseif ($data->order_status == 'cancelled') {
                            return '<span class="badge badge-danger">Cancelled</span>';
                        } elseif ($data->order_status == 'completed') {
                            return '<span class="badge badge-success">Completed</span>';
                        } elseif ($data->order_status == 'shipped') {
                            return '<span class="badge badge-info">Shipped</span>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="order/' . $data->id . '" class="btn btn-dark btn-sm">
                                <i class="fas fa-eye"></i>
                                </a>&nbsp;<a title="edit" href="order/' . $data->id . '/edit" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })
                    ->addIndexColumn()
                    ->rawColumns(['order_no', 'buyer', 'status', 'total_amount', 'order_date', 'checkbox', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('admin/dashboard')->with('error', 'SomeThing Went Wrong baby');
        }
        return view('admin.order.index');
    }

    public function show($id)
    {
        $order = Order::where('id', $id)->with('orderItems', 'buyer', 'orderItems.product', 'orderItems.shippingLabels')->firstOrFail();

        return view('admin.order.show', compact('order'));
    }

    public function edit($id)
    {
        try {
            $order = Order::where('id', $id)->with('orderItems.seller', 'buyer.buyerAddress', 'orderItems.product', 'orderItems.shippingLabels', 'state_name', 'city_name')->firstOrFail();
            $countries = Countries::all();

            return view('admin.order.edit', compact('order', 'countries'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function updateOrderTracking(Request $request, $id)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            "tracking_no"    => "required|array",
            "tracking_no.*"  => "required",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            if (!empty($request->label_id)) {
                foreach ($request->label_id as $label_id) {

                    foreach ($request->tracking_no[$label_id] as $index => $tracking_no) {

                        if ($request->file('shipping_doc_name')[$label_id][$index]) {
                            $shipping_doc_name = time() . '_' . $request->file('shipping_doc_name')[$label_id][$index]->getClientOriginalName();
                            $request->file('shipping_doc_name')[$label_id][$index]->move(public_path() . '/uploads/shipmentDocs/', $shipping_doc_name);

                            OrderShippingLabel::updateOrCreate([
                                'id' => $label_id,
                            ], [
                                'tracking_number' => $tracking_no,
                                'shipping_doc_name' => $shipping_doc_name
                            ]);
                        } else {
                            $shipping_doc_name = null;
                            OrderShippingLabel::updateOrCreate([
                                'id' => $label_id,
                            ], [
                                'tracking_number' => $tracking_no,
                            ]);
                        }
                    }
                }
            } else {

                foreach ($request->order_item_id as $order_item_id) {
                    foreach ($request->tracking_no[$order_item_id] as $index => $tracking_no) {

                        if ($request->file('shipping_doc_name')[$order_item_id][$index]) {
                            $shipping_doc_name = time() . '_' . $request->file('shipping_doc_name')[$order_item_id][$index]->getClientOriginalName();
                            $request->file('shipping_doc_name')[$order_item_id][$index]->move(public_path() . '/uploads/shipmentDocs/', $shipping_doc_name);

                            OrderShippingLabel::create([
                                'order_item_id' => $order_item_id,
                                'tracking_number' => $tracking_no,
                                'shipping_doc_name' => $shipping_doc_name
                            ]);
                        } else {
                            $shipping_doc_name = null;
                            OrderShippingLabel::create([
                                'order_item_id' => $order_item_id,
                                'tracking_number' => $tracking_no,
                            ]);
                        }
                    }
                }
            }

            return redirect()->back()->with('success', "Order Updated Successfully");
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function updateOrderAddress(Request $request, $id)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'buyer_name' => 'required',
            'email' => 'required',
            'phone_no' => 'required',
            'address1' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $order = Order::where('id', $id)->first();
            $order->buyer_name = $request->buyer_name;
            $order->buyer_email = $request->email;
            $order->phone_no = $request->phone_no;
            $order->address1 = $request->address1;
            $order->country = $request->country;
            $order->state = $request->state;
            $order->city = $request->city;
            $order->save();
            return redirect()->back()->with('success', "Order Updated Successfully");
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function changeOrderStatus(Request $request, $id)
    {
        $order = Order::where('id', $id);

        if ($order->count() > 0) {
            $order->update(['order_status' => $request->val]);
            return 1;
        } else {
            return 0;
        }
    }

    public function deleteOrder(Request $request, $id)
    {
        $order = Order::where('id', $id);

        if ($order->count() > 0) {
            $order->update(['deleted_at' => date('Y-m-d')]);
            return 1;
        } else {
            return 0;
        }
    }

    public function deleteBulkOrders(Request $request)
    {
        try {
            $orders = Order::whereIn('id', $request->id_array);
            if ($orders->count() > 0) {
                foreach ($orders->get() as $order) {
                    $order->deleted_at = date('Y-m-d');
                    $order->save();
                    $payment = Payment::where('order_id', $order->id);
                    if ($payment->count() > 0) {
                        $payment = $payment->first();
                        $payment->deleted_at = date('Y-m-d');
                        $payment->save();
                    }
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
