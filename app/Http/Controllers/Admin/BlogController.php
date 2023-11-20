<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if (request()->ajax()) {
                return datatables()->of(Blog::get())
                    ->addColumn('status', function ($data) {
                        if ($data->status == 'inActive') {
                            return '<label class="switch"><input type="checkbox"  data-id="' . $data->id . '" data-val="active"  id="status-switch"><span class="slider round"></span></label>';
                        } else {
                            return '<label class="switch"><input type="checkbox" checked data-id="' . $data->id . '" data-val="inActive"  id="status-switch"><span class="slider round"></span></label>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="blog/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;<a title="edit" href="blog/' . $data->id . '/edit" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['status', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.blogs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), array(
            'title' => 'required',
            'description' => 'required',
        ));
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        //image uploading

        if ($request->file('image')) {
            $image = time() . '_' . $request->file('image')->getClientOriginalName();
            //            $product_image_first_path = $request->file('product_image_first')->storeAs('products', $product_image_first);
            $request->file('image')->move(public_path() . '/uploads/blog/', $image);
        } else {
            $image = null;
        }

        $Blog = Blog::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'image' => $image,
        ]);

        return redirect('admin/blog')->with(['success' => 'Blog Added Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Blog::where('id', $id)->firstOrFail();
        return view('admin.blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $blog = Blog::where('id', $id)->firstOrFail();
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $blog = Blog::where('id', $id)->firstOrFail();

        try {
            $this->validate($request, array(
                'title' => 'required',
                'description' => 'required',
            ));
            $imageName = $blog->image;
            //image uploading
            if ($request->has('image')) {
                $image = time() . '.' . $request->file('image')->extension();
                $request->file('image')->move(public_path() . '/uploads/blog/', $image);
                $imageName = $image;
            }

            $blog->title = $request->input('title');
            $blog->description = $request->input('description');
            $blog->image =  $imageName;
            $blog->save();

            return redirect(route('blog.index'))->with('success', 'Blog Updated');
        } catch (\Exception $ex) {
            return redirect(route('blog.index'))->with('error', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $content = Blog::find($id);
        $content->delete(); //
        echo 1;
    }

    public function changeBlogStatus(Request $request, $id)
    {

        $product = Blog::where('id', $id);

        if ($product->count() > 0) {
            $product->update(['status' => $request->val]);
            return 1;
        }
    }
}
