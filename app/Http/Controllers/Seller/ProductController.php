<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OptionProduct;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductMetaData;
use App\Models\RelatedProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        try {
            $validator = Validator::make($input, array(
                'category' => 'required',
                'sub_category' => 'required',
                'title' => 'required',
                'product_type' => 'required',
                'qty' => 'required',
                'price' => 'required|numeric',
                'brand' => 'required',
                'shipping' => 'required',
                'condition' => 'required',
                'location' => 'required',
                'weight' => 'required_if:product_type,Physical',
                // 'weight_unit' => 'required_if:product_type,Physical',
                'length' => 'required_if:product_type,Physical',
                'width' => 'required_if:product_type,Physical',
                'height' => 'required_if:product_type,Physical',
                // 'dimensions_unit' => 'required_if:product_type,Physical',
                'description' => 'required',
                'product_picture' => 'required|image|mimes:jpeg,jpg,png|max:10000',
                'shipping_charges' => 'required_if:shipping,==,3',
            ), array(
                'shipping_charges.required_if' => 'The shipping charges field is required when shipping is Shipped By seller.'
            ));

            if ($validator->fails()) {
                return [
                    "status" => false,
                    "message" => "Product not updated!",
                    "errors" => $validator->messages()
                ];
            }
            //image uploading

            if ($request->file('product_picture')) {
                $product_image_first = time() . '_' . $request->file('product_picture')->getClientOriginalName();
                //            $product_image_first_path = $request->file('product_image_first')->storeAs('products', $product_image_first);
                $request->file('product_picture')->move(public_path() . '/uploads/products/', $product_image_first);
            } else {
                $product_image_first = null;
            }

            if ($request->file('product_file')) {
                $validator = Validator::make($input, [
                    'product_file' => 'required_if:product_type,Downloadable|mimes:zip',
                ]);

                if ($validator->fails()) {
                    return [
                        "status" => false,
                        "message" => "Product File Exception!",
                        "errors" => $validator->messages()
                    ];
                }

                $product_file = time() . '_' . $request->file('product_file')->getClientOriginalName();
                //            $product_image_first_path = $request->file('product_image_first')->storeAs('products', $product_image_first);
                $request->file('product_file')->move(public_path() . '/uploads/products/', $product_file);
            } else {
                $product_file = null;
            }

            $product = Product::create([
                'seller_id' => Auth::user()->seller->id,
                'category_id' => $request->get('category'),
                'sub_category_id' => $request->get('sub_category'),
                'product_name' => $request->get('title'),
                'product_current_price' => $request->get('price'),
                'discount_price' => $request->get('discount_price') ?? 0,
                'vat' =>  0,
                'price' => ($request->get('discount_price') > 0 ? $request->get('discount_price') : $request->get('price')),
                'product_condition' => $request->get('condition'),
                'description' => $request->get('description'),
                'status' => $request->get('status') ?? 1,
                'product_image' => $product_image_first,
                'manufacturer_id' => $request->input('brand'),
                'shipping' => $request->input('shipping'),
                'location_id' => $request->input('location'),
                'shipping_charges' => $request->input('shipping_charges') ?? null,
                'product_type' => $request->input('product_type'),
                'weight' => $request->input('weight'),
                // 'weight_unit' => $request->input('weight_unit'),
                'length' => $request->input('length'),
                'width' => $request->input('width'),
                'height' => $request->input('height'),
                // 'dimensions_unit' => $request->input('dimensions_unit'),
                'product_file' => $product_file,
                'qty' => $request->input('qty')
            ]);

            if ($request->has('additionalImages') && $request->file('additionalImages') !== null) {
                if (count($request->file('additionalImages')) > 0) {
                    foreach ($request->file('additionalImages') as $key => $add_image) {
                        $product_image_ad = time() . '_' . $add_image->getClientOriginalName();
                        //                $product_image_ad_path = $product_image->storeAs('products', $product_image_ad);
                        $add_image->move(public_path() . '/uploads/products/', $product_image_ad);

                        ProductImage::create([
                            'product_id' => $product->id,
                            'product_images' => $product_image_ad
                        ]);
                    }
                }
            }

            session()->flash('success', 'Product Added Successfully');
            return [
                "status" => true,
                "data" => [],
                "errors" => [],
                "message" => "Successfully added"
            ];
        } catch (\Exception $ex) {
            return [
                "status" => false,
                "data" => [],
                "errors" => $ex->getMessage(),
                "message" => $ex->getMessage()
            ];
        }
    }



    public function getSubCategories(Request $request)
    {

        $parent_id = $request->cat_id;

        $subcategories = Category::where('parent_id', $parent_id)->get();

        return response()->json([
            'subcategories' => $subcategories
        ]);
    }

    public function getProduct($id)
    {
        $product = Product::where('id', $id)->where('seller_id', Auth::user()->seller->id)->with('category', 'product_images')->first();
        $subCategory = Category::where('id', $product->sub_category_id)->first();
        // dd($subCategory);
        return response()->json([
            'product' => $product,
            'subCategory' => $subCategory
        ]);
    }

    public function update(Request $request)
    {
        $input = $request->all();
        try {
            $validator = Validator::make($input, array(
                'category' => 'required',
                'sub_category' => 'required',
                'title' => 'required',
                'product_type' => 'required',
                'qty' => 'required',
                'price' => 'required|numeric',
                'brand' => 'required',
                'shipping' => 'required',
                'condition' => 'required',
                'location' => 'required',
                'description' => 'required',
                'weight' => 'required_if:product_type,Physical',
                // 'weight_unit' => 'required_if:product_type,Physical',
                'length' => 'required_if:product_type,Physical',
                'width' => 'required_if:product_type,Physical',
                'height' => 'required_if:product_type,Physical',
                // 'dimensions_unit' => 'required_if:product_type,Physical',
                // 'product_file' => 'required_if:product_type,Downloadable|mimes:zip'
                'shipping_charges' => 'required_if:shipping,==,3',

            ), array(
                'shipping_charges.required_if' => 'The shipping charges field is required when shipping is Shipped By seller.'
            ));

            if ($validator->fails()) {
                return [
                    "status" => false,
                    "message" => "Product not added.",
                    "errors" => $validator->messages()
                ];
            }

            $product = Product::where('id', $request->id)->where('seller_id', Auth::user()->seller->id)->first();

            if ($request->file('product_file')) {
                $validator = Validator::make($input, [
                    'product_file' => 'required_if:product_type,Downloadable|mimes:zip',
                ]);

                if ($validator->fails()) {
                    return [
                        "status" => false,
                        "message" => "Product File Exception!",
                        "errors" => $validator->messages()
                    ];
                }
                $old_file = $product->product_file;
                $product_file = time() . '_' . $request->file('product_file')->getClientOriginalName();
                //            $product_image_first_path = $request->file('product_image_first')->storeAs('products', $product_image_first);
                $request->file('product_file')->move(public_path() . '/uploads/products/', $product_file);
                $product->product_file = $product_file;
            } else {
                $product_file = null;
                $old_file = null;
            }

            //image uploading

            if ($request->file('product_picture')) {
                $validator = Validator::make($input, [
                    'product_picture' => 'required|image|mimes:jpeg,jpg,png|max:10000'
                ]);

                if ($validator->fails()) {
                    return [
                        "status" => false,
                        "message" => "Product Picture Exception!",
                        "errors" => $validator->messages()
                    ];
                }

                $product_image_first = time() . '_' . $request->file('product_picture')->getClientOriginalName();
                //            $product_image_first_path = $request->file('product_image_first')->storeAs('products', $product_image_first);
                $request->file('product_picture')->move(public_path() . '/uploads/products/', $product_image_first);
                $product->product_image = $product_image_first;
            } else {
                $product_image_first = null;
            }

            $product->category_id = $request->get('category');
            $product->sub_category_id = $request->get('sub_category');
            $product->product_name = $request->get('title');
            $product->product_current_price = $request->get('price');
            $product->discount_price = $request->get('discount_price') ?? 0;
            $product->vat = 0;
            $product->price = ($request->get('discount_price') > 0 ? $request->get('discount_price') : $request->get('price'));
            $product->product_condition = $request->get('condition');
            $product->description = $request->get('description');
            $product->status = $request->get('status') ?? 1;
            $product->manufacturer_id = $request->input('brand');
            $product->shipping = $request->input('shipping');
            $product->location_id = $request->input('location');
            $product->shipping_charges = $request->input('shipping_charges');
            $product->product_type = $request->input('product_type');
            $product->weight = $request->input('weight');
            // $product->weight_unit = $request->input('weight_unit');
            $product->length = $request->input('length');
            $product->width = $request->input('width');
            $product->height = $request->input('height');
            // $product->dimensions_unit = $request->input('dimensions_unit');
            $product->qty = $request->qty;
            $product->status = 1;
            $product->save();

            /* Removing additional images other than ids */
            if (!empty($request->input('saved_images')) || $request->input('saved_images') !== 'null') {

                $savedImages = ProductImage::where('product_id', $request->id)->whereNotIn('id', $request->input('saved_images'))->get();
                if (count($savedImages) > 0) {
                    foreach ($savedImages as $image) {
                        $image->delete();
                    }
                }
            }

            if ($request->has('additionalImages') && $request->file('additionalImages') !== null) {
                if (count($request->file('additionalImages')) > 0) {

                    foreach ($request->file('additionalImages') as $key => $add_image) {
                        $product_image_ad = time() . '_' . $add_image->getClientOriginalName();
                        $add_image->move(public_path() . '/uploads/products/', $product_image_ad);

                        ProductImage::create([
                            'product_id' => $product->id,
                            'product_images' => $product_image_ad
                        ]);
                    }
                }
            }

            $old_file_path = public_path('uploads/products/' . $old_file);

            if (file_exists($old_file_path)) {
                File::delete($old_file_path);
            }


            session()->flash('success', 'Product Updated Successfully');

            return [
                "status" => true,
                "data" => [],
                "errors" => [],
                "message" => "Successfully updated"
            ];
        } catch (\Exception $ex) {
            return [
                "status" => false,
                "data" => [],
                "errors" => $ex->getMessage(),
                "message" => $ex->getMessage()
            ];
        }
    }

    public function removeProduct($id)
    {
        $product = Product::where('id', $id)->where('seller_id', Auth::user()->seller->id);
        if ($product->count() > 0) {
            $product->first();
            $product->update(['status' => 0]);
        }
        session()->flash('success', 'Product Deleted Successfully');
        return [
            "status" => true,
            "data" => [],
            "errors" => [],
            "message" => "Successfully deleted"
        ];
    }

    public function searchSellerProduct(Request $request)
    {
        return $products = Product::where('seller_id', Auth::user()->seller->id)->Where('product_name', 'like', '%' . $request->get('keyword') . '%')->with('category')->get();
    }

    public function cloneProduct(Request $request, $id)
    {
        $product = Product::where('seller_id', Auth::user()->seller->id)->Where('id', $id);
        $productImgs = ProductImage::where('product_id', $id);

        if ($product->count() > 0) {
            $product = $product->first();
            $newPost = $product->replicate();
            $newPost->created_at = Carbon::now();
            $newPost->save();

            if ($productImgs->count() > 0) {
                foreach ($productImgs->get() as $productImg) {
                    $newProductImgs = $productImg->replicate();
                    $newProductImgs->product_id = $newPost->id;
                    $newProductImgs->created_at = Carbon::now();
                    $newProductImgs->save();
                }
            }

            return response()->json([
                'status' =>  true,
                'message' => "Product Cloned Successfully!"
            ]);
        }
        return response()->json([
            'status' =>  false,
            'message' => "Product not found!"
        ]);
    }
}
