<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use Illuminate\Http\Request;

class AttributeGroupController extends Controller
{

    /*
     * SHOW ATTRIBUTE GROUP DETAIL
     * */
    public function index(Request $request){

    }


    /*
     * SHOW ATTRIBUTE GROUPS LISTING
     * */
    public function show(Request $request){

        try {
            if (request()->ajax()) {
                return datatables()->of(AttributeGroup::all())
                    ->addColumn('status', function ($data) {
                        if($data->status == 1){
                            return '<span class="badge badge-success">Enabled</span>';
                        }else{
                            return '<span class="badge badge-warning">Disabled</span>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="Edit" href="edit-attribute-group/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-pencil-alt"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['status','action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.catalog.attribute-groups');

    }

    /*
     * ADD ATTRIBUTE GROUP
     * */
    public function add(Request $request){

        try{

            if($request->method() == 'POST'){
                $names= $request->input('name');

                foreach ($names as $name){
                    $attrGroup = AttributeGroup::create([
                        'attribute_group' => $name
                    ]);
                }
                return redirect(route('catalog.attributeGroups'));
            }

            return view('admin.catalog.add-attribute-group');
        }
        catch(\Exception $ex){
            return redirect('admin/dashboard')->with('error',$ex->getMessage());

        }
    }

    /*
     * EDIT ATTRIBUTE GROUP
     * */
    public function edit(Request $request, $id){
        try{

            $attrGroup = AttributeGroup::find($id);
            if ($attrGroup == null){
                abort(404);
            }

            if($request->method() == 'POST'){
                $names= $request->input('name');
                $status = $request->input('status');
                foreach ($names as $name){
                    $attrGroup->attribute_group = $name;
                    $attrGroup->status = $status ? 1 : 0;
                }
                $attrGroup->save();
                return redirect(route('catalog.attributeGroups'));
            }

            return view('admin.catalog.add-attribute-group', compact('attrGroup'));
        }
        catch(\Exception $ex){
            return redirect('admin/dashboard')->with('error',$ex->getMessage());

        }
    }

    /*
     * DELETE ATTRIBUTE GROUP
     * */
    public function destroy(Request $request, $id){
        $group = AttributeGroup::find($id);
        $childCount = Attribute::where('attribute_group_id', $id)->count();
        if ($childCount == 0){
            if(!empty($group) ){
                $group->delete();
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 0;
        }

    }

}
