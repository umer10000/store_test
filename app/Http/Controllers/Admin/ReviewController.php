<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {

        try {
            if (request()->ajax()) {
                return datatables()->of(ProductReview::with('product', 'buyer')->get())
                    ->addColumn('product', function ($data) {
                        return $data->product->product_name;
                    })->addColumn('author', function ($data) {
                        if ($data->author !== null) {
                            return $data->author;
                        } else {
                            return $data->buyer->name;
                        }
                    })->addColumn('status', function ($data) {
                        if ($data->status == 1) {
                            return '<span class="badge badge-success">Enabled</span>';
                        } else {
                            return '<span class="badge badge-warning">Disabled</span>';
                        }
                    })->addColumn('date_added', function ($data) {
                        $date = date_create($data->created_at);
                        return date_format($date, 'd-m-Y');
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="review/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;<a title="Edit" href="review/edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['product', 'author', 'status', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.review.index');
    }

    public function show($id)
    {
        $product_review = ProductReview::where('id', $id)->with('product')->firstOrFail();
        return view('admin.review.show', compact('product_review'));
    }

    public function edit(Request $request, $id)
    {
        $product_review = ProductReview::where('id', $id)->with('product')->first();
        if (empty($product_review)) {
            abort(404);
        }
        if ($request->method() == 'POST') {
            try {
                $this->validate($request, array(
                    'product_name' => 'required',
                    'author' => 'required|min:3|max:25',
                    'description' => 'required|min:25|max:1000',
                    'rating' => 'required',
                    'status' => 'required',

                ));
                $product_review->author = $request->input('author');
                $product_review->description = $request->input('description');
                $product_review->rating = $request->input('rating');
                $product_review->status = $request->input('status') ? 1 : 0;
                $product_review->save();
                return redirect(route('review.index'))->with('success', 'Review Updated');
            } catch (\Exception $ex) {
                return redirect(route('review.index'))->with('error', $ex->getMessage());
            }
        }
        return view('admin.review.edit', compact('product_review'));
    }

    public function destroy($id)
    {
        $content = ProductReview::find($id);
        $content->delete(); //
        echo 1;
    }
}
