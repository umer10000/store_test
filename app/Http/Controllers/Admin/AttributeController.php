<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeGroup;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{

    /*
    * SHOW ATTRIBUTES LISTING
    * */
    public function show(Request $request){

        try {
            if (request()->ajax()) {
                return datatables()->of(Attribute::with('attributeGroup')->get())
                    ->addColumn('attribute_group', function ($data) {
                        return $data->attributeGroup->attribute_group ?? '';
                    }) ->addColumn('status', function ($data) {
                        if($data->status == 1){
                            return '<span class="badge badge-success">Enabled</span>';
                        }else{
                            return '<span class="badge badge-warning">Disabled</span>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="Edit" href="edit-attribute/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-pencil-alt"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['status','action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.catalog.attributes');

    }

    /*
     * ADD ATTRIBUTE
     * */
    public function add(Request $request){

        try{
            $attrGroups = AttributeGroup::where('status', 1)->get();
            if($request->method() == 'POST'){
                $names = $request->input('name');
                $group = $request->input('attr-group');

                foreach ($names as $name){
                    $attrGroup = Attribute::create([
                        'attribute_group_id'    => $group,
                        'attribute_name'        => $name
                    ]);
                }
                return redirect(route('catalog.attributes'));
            }

            return view('admin.catalog.add-attribute', compact('attrGroups'));
        }
        catch(\Exception $ex){
            return redirect('admin/dashboard')->with('error',$ex->getMessage());

        }
    }

    /*
     * EDIT ATTRIBUTE
     * */
    public function edit(Request $request, $id){

        try{
            $attrGroups = AttributeGroup::where('status', 1)->get();
            $attribute = Attribute::find($id);
            if ($attribute == null){
                abort(404);
            }

            if($request->method() == 'POST'){
                $names = $request->input('name');
                $group = $request->input('attr-group');
                $status = $request->input('status');

                foreach ($names as $name){
                    $attribute->attribute_group_id = $group;
                    $attribute->attribute_name = $name;
                    $attribute->status = $status ? 1 : 0;
                }
                $attribute->save();
                return redirect(route('catalog.attributes'));
            }

            return view('admin.catalog.add-attribute', compact('attrGroups', 'attribute'));
        }
        catch(\Exception $ex){
            return redirect('admin/dashboard')->with('error',$ex->getMessage());

        }
    }

    /*
     * DELETE ATTRIBUTE
     * */
    public function destroy(Request $request, $id){
        $attribute = Attribute::find($id);
        if(!empty($attribute) ){
            $attribute->delete();
            echo 1;
        }else{
            echo 2;
        }
    }
}
