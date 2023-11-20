<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cart;
use Validator;

class CartController extends Controller
{
    public function index()
    {
        $slug = null;

        $cartItem = Cart::content();
        $products = array();
        $totalAmount = 0;
        $vat_charges = 0;

        if (!empty($cartItem) && Cart::count() > 0) {
            foreach ($cartItem as $item) {

                $Product = Product::find($item->id);
                $seller = Seller::find($Product->seller_id);
                $category = Category::find($Product->category_id);

                $amount = (float)$item->price * (int)$item->qty;

                $totalAmount += $amount;
                $vat_charges += $Product->vat;

                $products[] = array(
                    'row_id' => $item->rowId,
                    'productId' => $item->id,
                    'product_image' => $Product->product_image,
                    'name' => $item->name,
                    'category' => $category->name,
                    'price' => $item->price,
                    'subtotal' => $amount,
                    'quantity' => $item->qty,
                    'options' => $item->options,
                    'product_type' => $Product->product_type,
                    'vat_charges' => $Product->vat,
                    'shop' => $seller->name,
                    'productQuantity' => $Product->qty,
                );
            }
        } else {
            // return redirect('/all-products');
        }
        return view('front.cart.index')->with([
            'products' => $products,
        ]);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric|between:1,100'
        ]);
        // dd($request->all());
        // if ($validator->fails()) {
        //     session()->flash('errors', collect(['Quantity must be between 1 and 10.']));
        //     return response()->json(['success' => false], 400);
        // }
        if ((int)$request->quantity > (int)$request->productQuantity) {

            session()->flash('error', 'We currently do not have enough items in stock.');
            return response()->json(['success' => false], 400);
        }
        Cart::update($id, $request->quantity);

        session()->flash('success', 'Item Quantity updated successfully!');
        return response()->json(['success' => true]);
    }

    public function store(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->role_id == 2) {
            return redirect()->back()->with("error", "Seller Can't add item in Cart!");
        }

        $data = $request->all();


        if (isset($data['option'])) {
            $attributes = Product::getCartOptions($data['option'], $id);
        }

        $Product = Product::find($id);
        $setting = \App\Models\Settings::find(1);
        $price = 0;

        if ($Product->discount_price > 0) {
            $price = ($Product->discount_price / 100) * $setting->service_charges;
        } else {
            $price = ($Product->product_current_price / 100) * $setting->service_charges;
        }

        $cart = [
            'id' => $Product->id,
            'name' => $Product->product_name,
            'qty' => $request->qty ? $request->qty : 1,
            'price' => ($Product->discount_price > 0) ? $Product->discount_price + $price : $Product->product_current_price + $price,
            'weight' => 0,
        ];
        if (isset($attributes)) {
            $cart['options'] = $attributes['options'];
            $cart['options']['options_id'] = $attributes['options_id'];
            $optional_total = $cart['price'] + $attributes['options_total'];
            $cart['price'] = $optional_total;
        }

        $cartItem = Cart::add($cart)->associate(Product::class);

        return redirect()->route('cart.index')->with('success_message', 'Item was added to your cart!');
    }

    public function destroy($id)
    {
        Cart::remove($id);

        return back()->with('success', 'Item has been removed!');
    }
}
