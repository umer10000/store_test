<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecialDealsPackage;
use Illuminate\Http\Request;

class SpecialDealPackageController extends Controller
{
    public function index()
    {
        try {
            if (request()->ajax()) {
                return datatables()->of(SpecialDealsPackage::orderBy('id', 'desc')->get())
                    ->addColumn('amount', function ($data) {
                        if ($data->amount !== null) {
                            return presentPrice($data->amount) ?? '';
                        }
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status == 0) {
                            return '<label class="switch"><input type="checkbox"  data-id="' . $data->id . '" data-val="1"  id="status-switch"><span class="slider round"></span></label>';
                        } else {
                            return '<label class="switch"><input type="checkbox" checked data-id="' . $data->id . '" data-val="0"  id="status-switch"><span class="slider round"></span></label>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="edit" href="specialDealPackage/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
                    })->addIndexColumn()->rawColumns(['status', 'is_approve', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', 'SomeThing Went Wrong!');
        }
        return view('admin.specialDealPackage.index');
    }

    public function editFeaturedPackage($id)
    {
        $package = SpecialDealsPackage::where('id', $id)->first();
        return view('admin.specialDealPackage.edit', compact('package'));
    }

    public function updatefeaturedPackage(Request $request, $id)
    {
        $package = SpecialDealsPackage::where('id', $id);

        if ($package->count() > 0) {
            $package->first();
            $package->update([
                'name' => $request->title,
                'days' => $request->days,
                'amount' => $request->amount,
            ]);
            return redirect()->to('admin/specialDealPackage')->with('succes', 'Package Updated Successfully!');
        } else {
            return redirect()->back()->with('error', 'No Package found!');
        }
    }
}
