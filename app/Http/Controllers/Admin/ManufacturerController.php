<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function index()
    {

        try {
            if (request()->ajax()) {
                return datatables()->of(Manufacturer::get())
                    ->addColumn('status', function ($data) {
                        if ($data->status == 0) {
                            return '<label class="switch"><input type="checkbox"  data-id="' . $data->id . '" data-val="1"  id="status-switch"><span class="slider round"></span></label>';
                        } else {
                            return '<label class="switch"><input type="checkbox" checked data-id="' . $data->id . '" data-val="0"  id="status-switch"><span class="slider round"></span></label>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="brands/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;<a title="Edit" href="brands/edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['status', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.manufacturer.index');
    }

    public function create(Request $request)
    {
        try {
            if ($request->method() == 'POST') {
                $this->validate($request, array(
                    'name' => 'required|unique:manufacturers'
                ));

                //image uploading
                if ($request->file('file')) {
                    $image = time() . '.' . $request->file('file')->extension();
                    $request->file('file')->move(public_path() . '/uploads/manufacturer/', $image);
                    $imageName = $image;
                }
                $sortOrder = $request->input('sort_order');
                Manufacturer::create([
                    'name' => $request->input('name'),
                    'image' => isset($imageName) ? $imageName : null,
                    'sort_order' => $sortOrder ? $sortOrder : 0,
                    'status' => $request->input('status') ? 1 : 0
                ]);
                return redirect(route('manufacturer.index'))->with('success', 'Brand Added');
            }
        } catch (\Exception $ex) {
            return redirect(route('manufacturer.index'))->with('error', $ex->getMessage());
        }
        return view('admin.manufacturer.create');
    }

    public function show($id)
    {
        $manufacturer = Manufacturer::where('id', $id)->first();
        if (empty($manufacturer)) {
            abort(404);
        }
        return view('admin.manufacturer.show', compact('manufacturer'));
    }

    public function edit(Request $request, $id)
    {
        $manufacturer = Manufacturer::where('id', $id)->first();
        if (empty($manufacturer)) {
            abort(404);
        }
        if ($request->method() == 'POST') {
            try {
                $this->validate($request, array(
                    'name' => 'required',
                ));
                $imageName = $manufacturer->image;
                //image uploading
                if ($request->has('file')) {
                    $image = time() . '.' . $request->file('file')->extension();
                    $request->file('file')->move(public_path() . '/uploads/manufacturer/', $image);
                    $imageName = $image;
                }
                $sortOrder = $request->input('sort_order');

                $manufacturer->name = $request->input('name');
                $manufacturer->image =  $imageName;
                $manufacturer->sort_order = isset($sortOrder) ? $sortOrder : 0;
                $manufacturer->status = $request->input('status') ? 1 : 0;
                $manufacturer->save();

                return redirect(route('manufacturer.index'))->with('success', 'Brand Updated');
            } catch (\Exception $ex) {
                return redirect(route('manufacturer.index'))->with('error', $ex->getMessage());
            }
        }
        return view('admin.manufacturer.edit', compact('manufacturer'));
    }

    public function destroy($id)
    {
        $productCheck = Product::where('manufacturer_id', $id)->count();

        if ($productCheck > 0) {
            echo 3;
            return;
        }

        $content = Manufacturer::find($id);
        $content->delete();
        echo 1;
    }

    public function changeStatus(Request $request, $id)
    {
        $manufacturer = Manufacturer::where('id', $id)->first();
        if (empty($manufacturer)) {
            return 0;
        }
        $status = $manufacturer->status;
        if ($status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        if ($request->method() == 'POST') {
            $manufacturer->status = $status;
            $manufacturer->save();
        }
        return 1;
    }
}
