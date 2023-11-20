<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\OptionValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OptionsController extends Controller
{

    /*
     * SHOW OPTIONS
     * */
    public function show(Request $request){

        try {
            if (request()->ajax()) {
                return datatables()->of(Option::all())
                    ->addColumn('status', function ($data) {
                        if($data->status == 1){
                            return '<span class="badge badge-success">Enabled</span>';
                        }else{
                            return '<span class="badge badge-warning">Disabled</span>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="Edit" href="edit-option/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-pencil-alt"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['status','action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.catalog.options');

    }

    /*
     * ADD OPTION
     * */
    public function add(Request $request){

        try{

            if($request->method() == 'POST'){
                $validator = Validator::make($request->all(), [
                    'option_name' => 'required|array|unique:options',
                    "option_name.*"  => "required|string|distinct"
                ]);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator->errors())->withInput();
                }
                $names= $request->input('option_name');

                foreach ($names as $name){
                    $slugStr = Str::of($name)->slug('-');
                    $option= Option::create([
                        'option_name' => $name,
                        'slug' => $this->createSlug($slugStr)
                    ]);
                }
                return redirect(route('catalog.options'));
            }

            return view('admin.catalog.add-options');
        }
        catch(\Exception $ex){
            return redirect('admin/dashboard')->with('error',$ex->getMessage());

        }
    }

    /*
     * EDIT OPTION
     * */
    public function edit(Request $request, $id){
        try{

            $option = Option::find($id);
            if ($option == null){
                abort(404);
            }

            if($request->method() == 'POST'){
                $names= $request->input('option_name');
                $status = $request->input('status');
                foreach ($names as $name){
                    $option->option_name = $name;
                    $option->status = $status ? 1 : 0;
                }
                $option->save();
                return redirect(route('catalog.options'));
            }

            return view('admin.catalog.add-options', compact('option'));
        }
        catch(\Exception $ex){
            return redirect('admin/dashboard')->with('error',$ex->getMessage());

        }
    }

    /*
     * DELETE OPTION
     * */
    public function destroy(Request $request, $id){
        $option = Option::find($id);
        $childCount = OptionValue::where('option_id', $id)->count();
        if ($childCount == 0){
            if(!empty($option) ){
                $option->delete();
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 0;
        }

    }


    private function createSlug($str)
    {
        $checkSlug = Option::where('slug', $str)->exists();
        if ($checkSlug) {
            $number = 1;
            while ($number) {
                $newSlug = $str . "-" . $number++;
                $checkSlug = Option::where('slug', $newSlug)->exists();
                if (!$checkSlug) {
                    $slug = $newSlug;
                    break;
                }
            }

        } else {
            $slug = $str;
        }
        return $slug;
    }
}
