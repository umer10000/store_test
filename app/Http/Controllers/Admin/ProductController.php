<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeGroup;
use App\Models\Category;
use App\Models\CollectionProduct;
use App\Models\Countries;
use App\Models\Manufacturer;
use App\Models\Option;
use App\Models\OptionProduct;
use App\Models\OptionValue;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductMetaData;
use App\Models\RelatedProduct;
use App\Models\SpecialDealsProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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
                return datatables()->of(Product::where('deleted_at', null)->with('category')->orderBy('id', 'desc')->get())
                    ->addColumn('category_id', function ($data) {
                        return $data->category->name ?? '';
                    })
                    ->addColumn('product_current_price', function ($data) {
                        return presentPrice($data->product_current_price) ?? '';
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status == 0) {
                            return '<label class="switch"><input type="checkbox"  data-id="' . $data->id . '" data-val="1"  id="status-switch"><span class="slider round"></span></label>';
                        } else {
                            return '<label class="switch"><input type="checkbox" checked data-id="' . $data->id . '" data-val="0"  id="status-switch"><span class="slider round"></span></label>';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        return '<a title="View" href="product/' . $data->id . '" class="btn btn-dark btn-sm"><i class="fas fa-eye"></i></a>&nbsp;</a>&nbsp;<a title="edit" href="product/' . $data->id . '/edit" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>&nbsp;<button title="Delete" type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    })->addIndexColumn()->rawColumns(['status', 'category_id', 'action'])->make(true);
            }
        } catch (\Exception $ex) {
            return redirect('/')->with('error', 'SomeThing Went Wrong baby');
        }
        return view('admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mainCategories = Category::where('status', 1)->where('parent_id', 0)->get();
        $subCategories = Category::where('status', 1)->get();

        $countries = Countries::where('status', 1)->get();
        $options = Option::where('status', 1)->get();
        $products = Product::whereStatus(1)->get();
        $manufacturers = Manufacturer::whereStatus(1)->get();

        return view('admin.product.create', compact('mainCategories', 'subCategories', 'countries', 'options', 'products', 'manufacturers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($request->all(), array(
            'main_category' => 'required',
            'product_name' => 'required',
            'current_price' => 'required|numeric',
            'discounted_price' => 'required|numeric',
            'description' => 'required',
            'manufacturer' => 'required',
            'product_type' => 'required',
            'shipping' => 'required',
            'condition' => 'required',
            'location' => 'required',
            'quantity' => 'required',
            'manufacturer' => 'required',
            'weight' => 'required_if:product_type,Physical',
            'length' => 'required_if:product_type,Physical',
            'width' => 'required_if:product_type,Physical',
            'height' => 'required_if:product_type,Physical',
            'product_file' => 'required_if:product_type,Downloadable|mimes:zip'

        ));

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            //image uploading

            if ($request->file('product_image_first')) {
                $product_image_first = time() . '_' . $request->file('product_image_first')->getClientOriginalName();
                $request->file('product_image_first')->move(public_path() . '/uploads/products/', $product_image_first);
            } else {
                $product_image_first = null;
            }

            if ($request->file('product_file')) {

                $validator = Validator::make($input, [
                    'product_file' => 'required_if:product_type,Downloadable|mimes:zip',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $product_file = time() . '_' . $request->file('product_file')->getClientOriginalName();
                $request->file('product_file')->move(public_path() . '/uploads/products/', $product_file);
            } else {
                $product_file = null;
                $old_file = null;
            }

            $product = Product::create([
                'seller_id' => Auth::user()->seller->id,
                'category_id' => $request->get('main_category'),
                'sub_category_id' => $request->get('sub_category'),
                'product_name' => $request->get('product_name'),
                'product_type' => $request->get('product_type'),
                'price' => ($request->get('discount_price') > 0 ? $request->get('discount_price') : $request->get('price')),
                'product_condition' => $request->get('condition'),
                'shipping' => $request->get('shipping'),
                'product_current_price' => $request->get('current_price'),
                'discount_price' => $request->get('current_price'),
                'condition' => $request->get('condition'),
                'location_id' => $request->get('location'),
                'qty' => $request->get('quantity') ?? '0',
                'length' => $request->get('length'),
                'width' => $request->get('width'),
                'height' => $request->get('height'),
                'weight' => $request->get('weight'),
                'description' => $request->get('description'),
                'status' => $request->get('status') ?? 0,
                'product_image' => $product_image_first,
                'product_file' => $product_file,
                'manufacturer_id' => $request->input('manufacturer') ? $request->input('manufacturer') : 0,

            ]);

            if ($request->file('product_image')) {

                foreach ($request->file('product_image') as $key => $product_image) {
                    $product_image_ad = time() . '_' . $product_image->getClientOriginalName();
                    $product_image_ad_path = $product_image->storeAs('products', $product_image_ad);
                    $product_image->move(public_path() . '/uploads/products/', $product_image_ad);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'product_images' => $product_image_ad
                    ]);
                }
            }
            return redirect('/admin/product')->with(['success' => 'Product Added Successfully']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::where('id', $id)->with('product_meta_data', 'product_images', 'products_options', 'products_options.option_val', 'products_attributes', 'products_attributes.attribute')->firstOrFail();

        // $product = Product::where('id', $id)->with('product_meta_data', 'product_images', 'products_options', 'products_attributes', 'products_attributes.attribute', 'manufacturer')->firstOrFail();
        return view('admin.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::where('id', $id)->with('product_images')->firstOrFail();
        $mainCategories = Category::where('status', 1)->where('parent_id', 0)->get();
        $subCategories = Category::where('status', 1)->where('parent_id', $product->category_id)->get();
        $countries = Countries::where('status', 1)->get();
        // $attributeGroups = AttributeGroup::with('attributes')->get();
        // $options = Option::where('status', 1)->get();
        // $option_values = OptionValue::where('status', 1)->get();
        // $relatedProducts = RelatedProduct::with('products')->where('product_id', $id)->get();
        // $products = Product::whereStatus(1)->where('id', '!=', $id)->get();
        $manufacturers = Manufacturer::whereStatus(1)->get();

        return view('admin.product.edit', compact('product', 'mainCategories', 'subCategories', 'manufacturers', 'countries'));
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
        $input = $request->all();

        $validator = Validator::make($request->all(), array(
            'main_category' => 'required',
            'product_name' => 'required',
            'current_price' => 'required|numeric',
            'discounted_price' => 'required|numeric',
            'description' => 'required',
            'manufacturer' => 'required',
            'product_type' => 'required',
            'shipping' => 'required',
            'condition' => 'required',
            'location' => 'required',
            'quantity' => 'required',
            'manufacturer' => 'required',
            'weight' => 'required_if:product_type,Physical',
            'length' => 'required_if:product_type,Physical',
            'width' => 'required_if:product_type,Physical',
            'height' => 'required_if:product_type,Physical',
            // 'product_file' => 'required_if:product_type,Downloadable|mimes:zip'
        ));

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product = Product::where('id', $id)->first();

        if ($request->file('product_file')) {
            $validator = Validator::make($input, [
                'product_file' => 'required_if:product_type,Downloadable|mimes:zip',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $old_file = $product->product_file;
            $product_file = time() . '_' . $request->file('product_file')->getClientOriginalName();
            $request->file('product_file')->move(public_path() . '/uploads/products/', $product_file);
            $product->product_file = $product_file;
        } else {
            $product_file = null;
            $old_file = null;
        }

        //image uploading

        if ($request->file('product_image_first')) {
            $product_image_first = time() . '_' . $request->product_image_first->getClientOriginalName();
            //            $product_image_first_path = $request->file('product_image_first')->storeAs('products', $product_image_first);
            $request->file('product_image_first')->move(public_path() . '/uploads/products/', $product_image_first);
            $product->product_image = $product_image_first;
        } else {
            $product_image_first = null;
        }

        $product->category_id  = $request->get('main_category');
        $product->sub_category_id = $request->get('sub_category');
        $product->product_name = $request->get('product_name');
        $product->description = $request->get('description');
        $product->product_type = $request->get('product_type');
        $product->shipping = $request->get('shipping');
        $product->product_current_price = $request->get('current_price');
        $product->discount_price = $request->get('discounted_price');
        $product->price = ($request->get('discount_price') > 0 ? $request->get('discount_price') : $request->get('current_price'));
        $product->product_condition = $request->get('condition');
        $product->location_id = $request->get('location');
        $product->qty = $request->get('quantity');
        $product->product_condition = $request->get('condition');
        $product->length = $request->get('length');
        $product->width = $request->get('width');
        $product->height = $request->get('height');
        $product->weight = $request->get('weight');
        $product->status = $request->get('status') ?? 0;
        $product->manufacturer_id =  $request->input('manufacturer') ? $request->input('manufacturer') : 0;
        $product->save();



        /* Removing additional images other than ids */
        if (!empty($request->input('saved_images'))) {
            $savedImages = ProductImage::where('product_id', $id)->whereNotIn('id', $request->input('saved_images'))->get();
            if (count($savedImages) > 0) {
                foreach ($savedImages as $image) {
                    $image->delete();
                }
            }
        } else {
            $savedImages = ProductImage::where('product_id', $id)->get();
            if (count($savedImages) > 0) {
                foreach ($savedImages as $image) {
                    $image->delete();
                }
            }
        }

        /* Add additional images */
        if ($request->file('product_image')) {
            foreach ($request->file('product_image') as $key => $product_image) {
                $product_image_ad = time() . '_' . $product_image->getClientOriginalName();
                //                $product_image_ad_path = $product_image->storeAs('products', $product_image_ad);
                $product_image->move(public_path() . '/uploads/products/', $product_image_ad);

                ProductImage::create([
                    'product_id' => $product->id,
                    'product_images' => $product_image_ad,
                ]);
            }
        }
        //Attributes
        // ProductAttribute::where('product_id', $id)->delete();
        // if (!empty($request->get('attribute'))) {
        //     foreach ($request->get('attribute') as $keyA => $attributes) {
        //         ProductAttribute::create([
        //             'product_id' => $product->id,
        //             'attribute_id' => $attributes,
        //             'value' => $request->get('attribute_value')[$keyA],
        //         ]);
        //     }
        // }

        //Options
        // OptionProduct::where('product_id', $id)->delete();
        // if (!empty($request->get('option_id'))) {
        //     foreach ($request->get('option_id') as $keyO => $options) {
        //         OptionProduct::create([
        //             'product_id' => $id,
        //             'option_id' => $options,
        //             'option_val_id' => $request->get('option_value_id')[$keyO] ?? 0,
        //             'price' => $request->get('option_value_price')[$keyO] ?? 0,
        //             'qty' => $request->get('option_value_qty')[$keyO] ?? 0
        //         ]);
        //     }
        // }

        // // Related Products
        // RelatedProduct::where('product_id', $id)->delete();
        // if (!empty($request->get('related_prod_id'))) {
        //     foreach (array_unique($request->get('related_prod_id')) as $relatedProduct) {
        //         RelatedProduct::create([
        //             'product_id' => $id,
        //             'related_id' => $relatedProduct
        //         ]);
        //     }
        // }

        return redirect()->back()->with(['success' => 'Product Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //echo $id; die;
        $product = Product::where('id', $id);

        if ($product->count() > 0) {
            $product->update(['deleted_at' => date('Y-m-d')]);
            return 1;
        }

        // $check = Order::where('product_id', $id)->count();
        // if ($check > 0) {
        //     echo 0;
        //     return;
        // }

        // $deals = SpecialDealsProducts::where('product_id', $id)->count();
        // if ($deals > 0) {
        //     echo 2;
        //     return;
        // }
        // $content = Product::find($id);
        // $content->delete(); //
        // echo 1;
    }

    public function getSubCategories(Request $request)
    {

        $parent_id = $request->cat_id;

        $subcategories = Category::where('parent_id', $parent_id)->where('parent_id', '!=', 0)->get();
        return response()->json([
            'subcategories' => $subcategories
        ]);
    }

    public function checkProductSku(Request $request)
    {

        $sku = $request->sku;

        $product_sku = Product::where('sku', $sku)->count();

        return response()->json([
            'product_sku' => $product_sku
        ]);
    }

    public function checkProductSlug(Request $request)
    {
        $slug = $request->slug;

        $product_slug = Product::where('slug', $slug)->count();

        return response()->json([
            'product_slug' => $product_slug
        ]);
    }

    public function changeProductStatus(Request $request, $id)
    {

        $product = Product::where('id', $id);

        if ($product->count() > 0) {
            $product->update(['status' => $request->val]);
            return 1;
        }
    }

    public function getOptionValues(Request $request)
    {
        $option_id = $request->option_id;

        $OptionValue = OptionValue::where('option_id', $option_id)->get();
        return response()->json([
            'OptionValues' => $OptionValue
        ]);
    }
}
