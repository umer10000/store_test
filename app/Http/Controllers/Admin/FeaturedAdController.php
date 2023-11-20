<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedAd;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;

class FeaturedAdController extends Controller
{
    public function index()
    {
        // dd(FeaturedAd::with('seller')->orderBy('id')->get());
        try {
            if (request()->ajax()) {
                return datatables()->of(FeaturedAd::with('seller')->orderBy('id', 'desc')->get())
                    ->addColumn('seller', function ($data) {
                        if ($data->seller_id !== null) {
                            return $data->seller->name ?? '';
                        }
                    })
                    ->addColumn('amount', function ($data) {
                        if ($data->amount !== null) {
                            return presentPrice($data->amount) ?? '';
                        }
                    })
                    ->addColumn('start_date', function ($data) {
                        return date('d-m-Y', strtotime($data->start_date)) ?? '';
                    })
                    ->addColumn('end_date', function ($data) {
                        return date('d-m-Y', strtotime($data->end_date)) ?? '';
                    })
                    ->addColumn('banner', function ($data) {
                        return '<img src="' . featuredAddImage($data->banner) . '" class="img-fluid w-100" />';
                    })
                    ->addColumn('is_approve', function ($data) {
                        if ($data->is_approve == null) {
                            return '<label class="switch"><input type="checkbox"  data-id="' . $data->id . '" data-val="yes"  id="status-switch"><span class="slider round"></span></label>';
                        } else {
                            return '<label class="switch"><input type="checkbox" checked data-id="' . $data->id . '" data-val=null  id="status-switch"><span class="slider round"></span></label>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="edit" href="featuredAdsEdit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->addIndexColumn()->rawColumns(['seller', 'banner', 'is_approve', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', 'SomeThing Went Wrong!');
        }
        return view('admin.featured.index');
    }

    public function create()
    {
        return view('admin.featured.create');
    }

    public function store(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'start_date' => 'required',
            'end_date' => 'required',
            'banner' => 'required|mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if ($request->file('banner')) {
            $file = $request->file('banner');
            $new_image_name = date('YmdHis') . '-' . $file->getClientOriginalName();
            $file->move(public_path() . '/uploads/featured_ads/', $new_image_name);
            $banner = $new_image_name;

            $todaysDate = $request->start_date;
            $endDate = $request->end_date;

            $featuredAdId = FeaturedAd::create([
                'banner' => $banner,
                'start_date' => $todaysDate,
                'end_date' => $endDate,
                'status' => 1,
                'is_approve' => "yes",
            ]);

            return Redirect::route('featuredAds.index')->withSuccess('Banner Submited for Featured');
        }
    }

    public function edit($id)
    {
        $FeaturedAd = FeaturedAd::where('id', $id)->first();
        return view('admin.featured.edit', compact('FeaturedAd'));
    }

    public function update(Request $request, $id)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $featuredAdId = FeaturedAd::where('id', $id)->first();

        if ($request->file('banner')) {
            $validator = Validator::make($inputs, [
                'banner' => 'required|mimes:jpeg,jpg,png',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $file = $request->file('banner');
            $new_image_name = date('YmdHis') . '-' . $file->getClientOriginalName();
            $file->move(public_path() . '/uploads/featured_ads/', $new_image_name);
            $banner = $new_image_name;
            $featuredAdId->banner = $banner;
        }

        $todaysDate = $request->start_date;
        $endDate = $request->end_date;


        $featuredAdId->start_date = $todaysDate;
        $featuredAdId->end_date = $endDate;
        $featuredAdId->status = 1;
        $featuredAdId->is_approve = "yes";
        $featuredAdId->save();

        return Redirect::route('featuredAds.index')->withSuccess('Record Updated Successfully!');
    }


    public function changeFeaturedAdStatus(Request $request, $id)
    {

        $product = FeaturedAd::where('id', $id);

        if ($product->count() > 0) {
            $product->update(['is_approve' => $request->val]);
            return 1;
        }
    }

    public function destroy($id)
    {
        $deal = FeaturedAd::where('id', $id)->first();
        $payment = Payment::where('featured_ad_id', $id);
        if ($payment->count() > 0) {
            $payment->delete();
        }
        $deal->delete();
        echo 1;
    }
}
