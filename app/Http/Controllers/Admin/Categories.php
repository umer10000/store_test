<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use DB;

class Categories extends Controller
{
    public function __construct()
    {
        //
    }

    final public function index()
    {

        try {
            if (request()->ajax()) {
                return datatables()->of(Category::with('sub_category')->orderBy('id', 'desc')->get())
                    ->addColumn('parent_id', function ($data) {
                        return $data->sub_category->name ?? 'NULL';
                    })
                    ->addColumn('is_top_cat', function ($data) {
                        if ($data->is_top_cat == 1) {
                            return '<label class="switch top_cat"><input type="checkbox" checked data-id="' . $data->id . '" data-val="0"  id="status-switch"><span class="slider round"></span></label>';
                        } else {
                            return '<label class="switch top_cat"><input type="checkbox"  data-id="' . $data->id . '" data-val="1"  id="status-switch"><span class="slider round"></span></label>';
                        }
                    })
                    ->addColumn('shop_now', function ($data) {
                        if ($data->is_shop_now == 1) {
                            return '<label class="switch shop_now"><input type="checkbox" checked data-id="' . $data->id . '" data-val="0"  id="status-switch-shopNow"><span class="slider round"></span></label>';
                        } else {
                            return '<label class="switch shop_now"><input type="checkbox"  data-id="' . $data->id . '" data-val="1"  id="status-switch-shopNow"><span class="slider round"></span></label>';
                        }
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status == 1) {
                            return '<label class="switch status"><input type="checkbox" checked data-id="' . $data->id . '" data-val="0"  id="status-status"><span class="slider round"></span></label>';
                        } else {
                            return '<label class="switch status"><input type="checkbox"  data-id="' . $data->id . '" data-val="1"  id="status-status"><span class="slider round"></span></label>';
                        }
                    })
                    ->addColumn('mark', function ($data) {
                        if ($data->mark == 1) {
                            return '<label class="switch mark"><input type="checkbox" checked data-id="' . $data->id . '" data-val="0"  id="status-mark"><span class="slider round"></span></label>';
                        } else {
                            return '<label class="switch mark"><input type="checkbox"  data-id="' . $data->id . '" data-val="1"  id="status-mark"><span class="slider round"></span></label>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="category-view/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;<a title="edit" href="category-edit/' . $data->id . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->rawColumns(['parent_id', 'is_top_cat', 'shop_now', 'status', 'mark', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', $ex->getMessage());
        }
        return view('admin.category.list');
    }

    public function addCategory(Request $request)
    {

        if ($request->method() == 'POST') {
            $this->validate($request, array(
                'name' => 'required|unique:categories'
            ));
            //image uploading
            if ($request->file('file')) {
                $image = time() . '.' . $request->file('file')->extension();
                //                $request->file('file')->storeAs('upload/category', $image);
                $request->file('file')->move(public_path() . '/uploads/category/', $image);
                $imageName = $image;
            }

            //Banner uploading
            if ($request->file('cat_banner')) {
                $bImage = time() . '_banner.' . $request->file('cat_banner')->extension();
                $request->file('cat_banner')->move(public_path() . '/uploads/category/', $bImage);
                $imageBannerName = $bImage;
            }

            $slugStr = Str::of($request->input('name'))->slug('-');
            $category = Category::create([
                'name' => $request->input('name'),
                'parent_id' => $request->input('main-category'),
                'category_slug' => $this->createSlug($slugStr),
                'description' => $request->input('description'),
                'meta_tag_title' => $request->input('meta-title'),
                'meta_tag_description' => $request->input('meta-description'),
                'meta_tag_keywords' => $request->input('meta-keywords'),
                'category_image' => isset($imageName) ? $imageName : null,
                'category_banner' => isset($imageBannerName) ? $imageBannerName : null,
                'orderbymenu' => $request->input('orderbymenu'),
                'orderbytopcategory' => $request->input('orderbytopcategory'),
                'orderbyshopnow' => $request->input('orderbyshopnow'),
            ]);

            if ($category) {
                return redirect()->to('admin/category')->with(['success' => 'Category Added Successfully']);
            }
        }
        $mainCategories = Category::where('status', 1)->where('parent_id', 0)->get();
        return view('admin.category.add-category', compact('mainCategories'));
    }

    private function createSlug($str)
    {
        $checkSlug = Category::where('category_slug', $str)->exists();
        if ($checkSlug) {
            $number = 1;
            while ($number) {
                $newSlug = $str . "-" . $number++;
                $checkSlug = Category::where('category_slug', $newSlug)->exists();
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


    final public function edit(Request $request, $id)
    {

        if ($request->method() == 'POST') {
            $this->validate($request, array(
                'name' => ['required', Rule::unique('categories')->ignore($id)]
            ));

            if ($request->has('main-category') && $request->get('main-category') != 0) {
                $main_category = $request->input('main-category');
                $mainCategory = Category::find($main_category);
                if ($mainCategory->name == $request->input('name')) {
                    return redirect()->back()->with(['err' => "Parent and Child Category can't be same"])->withInput();
                }
            }
            $category = Category::find($id);

            //image uploading
            if ($request->file('file')) {
                $image = time() . '.' . $request->file('file')->extension();
                //                $request->file('file')->storeAs('upload/category', $image);
                $request->file('file')->move(public_path() . '/uploads/category/', $image);
                $imageName = $image;
                $category->category_image = $imageName;
            }

            //Banner uploading
            if ($request->file('cat_banner')) {
                $bannerImageName = time() . '_banner.' . $request->file('cat_banner')->extension();
                //                $request->file('file')->storeAs('upload/category', $image);
                $request->file('cat_banner')->move(public_path() . '/uploads/category/', $bannerImageName);
                // $bannerImageName = $image;
                $category->category_banner = $bannerImageName;
            }

            $category->name = $request->input('name');
            $category->parent_id = $request->input('main-category');
            $category->description = $request->input('description');
            $category->meta_tag_title = $request->input('meta-title');
            $category->meta_tag_description = $request->input('meta-description');
            $category->meta_tag_keywords = $request->input('meta-keywords');
            $category->orderbymenu         = $request->input('orderbymenu');
            $category->orderbytopcategory  = $request->input('orderbytopcategory');
            $category->orderbyshopnow      = $request->input('orderbyshopnow');

            if ($category->save()) {
                return redirect()->to('admin/category')->with(['success' => 'Category Edit Successfully']);
            }
        } else {
            $content = Category::findOrFail($id);
            $mainCategories = Category::where('status', 1)->where('parent_id', 0)->get();
            return view('admin.category.add-category', compact('mainCategories', 'content'));
        }
    }
    final public function destroy(int $id)
    {
        $productCheck = Product::where(function ($q) use ($id) {
            $q->where('category_id', $id)->orWhere('sub_category_id', $id);
        })->where('status', 1)->count();

        // dd($productCheck);
        if ($productCheck > 0) {
            echo 3;
            return;
        }
        $content = Category::find($id);

        if ($content->parent_id == 0) {
            $count = Category::where('parent_id', $id)->count();
            if ($count == 0) {
                $content->delete();
                echo 1;
            } else {
                echo 0;
                return;
            }
        } else {
            $content->delete(); //
            echo 1;
        }
    }

    final public function show(int $id)
    {

        $content = Category::with('sub_category')->find($id);
        return view('admin.category.view', compact('content'));
    }

    public function markCategoryAsTop(Request $request, $id)
    {

        $category = Category::where('id', $id);

        if ($category->count() > 0) {
            $category->update(['is_top_cat' => $request->val]);
            return 1;
        }
    }

    public function markCategoryAsShopNow(Request $request, $id)
    {

        $category = Category::where('id', $id);

        if ($category->count() > 0) {
            $category->update(['is_shop_now' => $request->val]);
            return 1;
        }
    }

    public function statusDisableEnable(Request $request, $id)
    {

        $category = Category::where('id', $id);

        if ($category->count() > 0) {
            $category->update(['status' => $request->val]);
            return 1;
        }
    }

    public function markDisableEnable(Request $request, $id)
    {
        // dd($request->all());
        $category = Category::where('id', $id);

        if ($category->count() > 0) {
            $category->update(['mark' => $request->val]);
            return 1;
        } else {
            return 0;
        }
    }

    public function toggleCategoryStatuses(Request $request)
    {

        try {
            if ($request->type == "top_categoy") {
                $categories = Category::whereIn('id', $request->ids)->orderBy('id', 'desc')->get();

                foreach ($categories as $category) {
                    $category->is_top_cat = $request->enum_status;
                    $category->save();
                }
            } elseif ($request->type == "shop_now") {
                $categories = Category::whereIn('id', $request->ids)->orderBy('id', 'desc')->get();
                foreach ($categories as $category) {
                    $category->is_shop_now = $request->enum_status;
                    $category->save();
                }
            } elseif ($request->type == "status") {
                $categories = Category::whereIn('id', $request->ids)->orderBy('id', 'desc')->get();
                foreach ($categories as $category) {
                    $category->status = $request->enum_status;
                    $category->save();
                }
            } elseif ($request->type == "mark") {
                $categories = Category::whereIn('id', $request->ids)->orderBy('id', 'desc')->get();
                foreach ($categories as $category) {
                    $category->mark = $request->enum_status;
                    $category->save();
                }
            }

            return response()->json([
                'status' => true,
                'message' => "Records Updated Successfully",
            ]);
        } catch (\Exception $ex) {

            return response()->json([
                'status' => false,
                'message' => $ex,
            ]);
        }


        // if ($category->count() > 0) {
        //     $category->update(['mark' => $request->val]);
        //     return 1;
        // } else {
        //     return 0;
        // }
    }
}
