<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\OptionValue;
use Illuminate\Http\Request;

class OptionValuesController extends Controller
{

    /*
    * SHOW ATTRIBUTES LISTING
    * */
    public function show(Request $request){

        try {
            if (request()->ajax()) {
                return datatables()->of(OptionValue::with('option')->get())
                    ->addColumn('option_name', function ($data) {
                        return $data->option->option_name ?? '';
                    })
                    ->addColumn('status', function ($data) {
                        if($data->status == 1){
                            return '<span class="badge badge-success">Enabled</span>';
                        }else{
                            return '<span class="badge badge-warning">Disabled</span>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="Edit" href="edit-option-value/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-pencil-alt"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['status','action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.catalog.option-values');

    }

    /*
     * ADD Option Values
     * */
    public function add(Request $request){

        try{
            $options = Option::where('status', 1)->get();
            if($request->method() == 'POST'){
                $optionValues = $request->input('option-value');
                $option = $request->input('option-name');
                foreach ($optionValues as $key => $value){
                    $optionValue = OptionValue::create([
                        'option_id'     => $option,
                        'option_value'  => $value,
                    ]);
                }
                return redirect(route('catalog.optionValues'));
            }

            return view('admin.catalog.add-option-value', compact('options'));
        }
        catch(\Exception $ex){
            return redirect('admin/dashboard')->with('error',$ex->getMessage());

        }
    }

    /*
     * EDIT Option Value
     * */
    public function edit(Request $request, $id){

        try{
            $options = Option::where('status', 1)->get();
            $optionValue = OptionValue::find($id);
            if ($optionValue == null){
                abort(404);
            }

            if($request->method() == 'POST'){
                $optionValues = $request->input('option-value');
                $option = $request->input('option-name');
                $status = $request->input('status');

                foreach ($optionValues as $key => $value){
                    $optionValue->option_id     = $option;
                    $optionValue->option_value  = $value;
                    $optionValue->status        = $status ? 1 : 0;
                }
                $optionValue->save();
                return redirect(route('catalog.optionValues'));
            }

            return view('admin.catalog.add-option-value', compact('options', 'optionValue'));
        }
        catch(\Exception $ex){
            return redirect('admin/dashboard')->with('error',$ex->getMessage());

        }
    }

    /*
     * DELETE Option Value
     * */
    public function destroy(Request $request, $id){
        $optionValue = OptionValue::find($id);
        if(!empty($optionValue) ){
            $optionValue->delete();
            echo 1;
        }else{
            echo 2;
        }
    }
}
