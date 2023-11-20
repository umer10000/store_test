<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsLetter;
use Illuminate\Http\Request;

class NewsLetterController extends Controller
{
    public function index()
    {

        try {
            if (request()->ajax()) {
                return datatables()->of(NewsLetter::get())
                    ->addColumn('status', function ($data){
                        if($data->status == 0){
                            return '<label class="switch"><input type="checkbox"  data-id="'.$data->id.'" data-val="1"  id="status-switch"><span class="slider round"></span></label>';
                        }else{
                            return '<label class="switch"><input type="checkbox" checked data-id="'.$data->id.'" data-val="0"  id="status-switch"><span class="slider round"></span></label>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['status','action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.newsletter.index');
    }


    public function edit(Request $request, $id){
        $subscription = NewsLetter::where('id',$id)->first();
        if(empty($subscription)){
           return 0;
        }
        $status = $subscription->status;
        if($status == 0){
            $status = 1;
        }else{
            $status = 0;
        }
        if($request->method() == 'POST'){
            $subscription->status = $status;
            $subscription->save();
        }
        return 1;
    }

    public function destroy($id)
    {
        $content = NewsLetter::find($id);
        $content->delete();
        echo 1;

    }
}
