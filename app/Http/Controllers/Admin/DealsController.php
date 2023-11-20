<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\SpecialDealsPackage;
use App\Models\SpecialDealsProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class DealsController extends Controller
{
    public function index()
    {
        // dd(SpecialDealsProducts::with('product', 'product.category')->get());
        try {
            if (request()->ajax()) {
                return datatables()->of(SpecialDealsProducts::with('seller', 'product', 'product.category')->orderBy('id', 'desc')->get())
                    ->addColumn('product_name', function ($data) {
                        return $data->product->product_name ?? '';
                    })
                    ->addColumn('seller', function ($data) {
                        return $data->seller->name ?? '';
                    })
                    ->addColumn('amount', function ($data) {
                        return presentPrice($data->amount) ?? '';
                    })
                    ->addColumn('start_date', function ($data) {
                        return date('d-m-Y', strtotime($data->start_date)) ?? '';
                    })
                    ->addColumn('end_date', function ($data) {
                        return date('d-m-Y', strtotime($data->end_date)) ?? '';
                    })
                    ->addColumn('is_approve', function ($data) {
                        if ($data->is_approve == null) {
                            return '<label class="switch"><input type="checkbox"  data-id="' . $data->id . '" data-val="yes"  id="status-switch"><span class="slider round"></span></label>';
                        } else {
                            return '<label class="switch"><input type="checkbox" checked data-id="' . $data->id . '" data-val=null  id="status-switch"><span class="slider round"></span></label>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="product/' . $data->product->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;<a title="edit" href="deals/' . $data->id . '/edit" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['seller', 'product_name', 'is_approve', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', 'SomeThing Went Wrong!');
        }
        return view('admin.deals.index');
    }

    public function changeDealsProductStatus(Request $request, $id)
    {

        $product = SpecialDealsProducts::where('id', $id);

        if ($product->count() > 0) {
            $product->update(['is_approve' => $request->val]);
            return 1;
        }
    }

    public function destroy($id)
    {
        $deal = SpecialDealsProducts::where('id', $id)->first();
        $payment = Payment::where('special_deal_product_id', $id)->delete();
        $deal->delete(); //
        echo 1;
    }


    public function create()
    {
        $products = Product::where('deleted_at', null)->where('status', 1)->whereHas('seller.sellerAddress')
            ->whereHas('seller', function ($q) {
                $q->where('deleted_at', null);
            })
            ->whereHas('category', function ($q) {
                $q->where('status', 1);
            })->with(['sub_category' => function ($q) {
                $q->where('status', 1);
            }])
            ->orderBy('id', 'desc')->take(16)
            ->get();
        $deals = SpecialDealsPackage::all();
        return view('admin.deals.create', compact('products', 'deals'));
    }

    public function store(Request $request)
    {
        try {
            $inputs = $request->all();
            $validator = Validator::make($inputs, [
                'product_id' => 'required',
                'deal_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            SpecialDealsProducts::create([
                'product_id' => $request->product_id,
                'special_deals_id' => $request->deal_id,
                'seller_id' => Auth::user()->seller->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'amount' => 0,
                'status' => 1,
                'is_approve' => "yes",
            ]);

            return redirect('admin/deals')->with('success', 'Special Deal Added Successfully.');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function edit($id)
    {
        $dealProduct = SpecialDealsProducts::where('id', $id)->first();
        $products = Product::where('deleted_at', null)->where('status', 1)->whereHas('seller.sellerAddress')
            ->whereHas('seller', function ($q) {
                $q->where('deleted_at', null);
            })
            ->whereHas('category', function ($q) {
                $q->where('status', 1);
            })->with(['sub_category' => function ($q) {
                $q->where('status', 1);
            }])
            ->orderBy('id', 'desc')->take(16)
            ->get();
        $deals = SpecialDealsPackage::all();
        return view('admin.deals.edit', compact('dealProduct', 'deals', 'products'));
    }

    public function update(Request $request, $id)
    {
        try {

            $inputs = $request->all();
            $validator = Validator::make($inputs, [
                'product_id' => 'required',
                'deal_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            SpecialDealsProducts::where('id', $id)->update([
                'product_id' => $request->product_id,
                'special_deals_id' => $request->deal_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date
            ]);

            return redirect('admin/deals')->with('success', 'Special Deal Updated Successfully.');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }
}
