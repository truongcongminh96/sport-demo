<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Wishlist;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        if (Session::has('coupon')) {
            Session::forget('coupon');
        }
        $product = Product::findOrFail($id);

        if ($product->discount_price == NULL) {
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thumbnail,
                    'color' => $request->color,
                    'size' => $request->size
                ]
            ]);

            return response()->json(['success' => 'Successfully added on your cart']);
        } else {
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thumbnail,
                    'color' => $request->color,
                    'size' => $request->size
                ]
            ]);
            return response()->json(['success' => 'Successfully added on your cart']);
        }
    }

    public function addMiniCart()
    {
        $carts = Cart::content();
        $cartQty = Cart::count();
        $cartTotal = Cart::total();

        return response()->json([
            'carts' => $carts,
            'cart_qty' => $cartQty,
            'cart_total' => $cartTotal
        ]);
    }

    public function removeMiniCart($rowId)
    {
        Cart::remove($rowId);
        return response()->json(['success' => 'Product remove from cart']);
    }

    public function addToWishlist(Request $request, $productId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'You need login your account']);
        }

        $exists = Wishlist::where(['user_id' => Auth::id(), 'product_id' => $productId])->first();

        if (!!$exists) {
            return response()->json(['error' => 'Product has been added your wishlist']);
        }

        Wishlist::insert([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'created_at' => Carbon::now()
        ]);

        return response()->json(['success' => 'Added your wishlist']);
    }

    public function couponApply(Request $request)
    {
        $coupon = Coupon::where(['coupon_name' => $request->coupon_name])->where('coupon_validity', '>=', Carbon::now()->format('Y-m-d'))->first();
        if (!$coupon) {
            return response()->json(['error' => 'Invalid Coupon']);
        }

        Session::put('coupon', [
            'coupon_name' => $coupon->coupon_name,
            'coupon_discount' => $coupon->coupon_discount,
            'discount_amount' => ((int)Cart::total()) * $coupon->coupon_discount / 100,
            'total_amount' => Cart::total() - ((int)Cart::total()) * $coupon->coupon_discount / 100
        ]);

        return response()->json([
            'success' => 'Coupon apply success'
        ]);
    }

    public function couponCalculation()
    {
        if (Session::has('coupon')) {
            return response()->json([
                'subtotal' => Cart::total(),
                'coupon_name' => session()->get('coupon')['coupon_name'],
                'coupon_discount' => session()->get('coupon')['coupon_discount'],
                'discount_amount' => session()->get('coupon')['discount_amount'],
                'total_amount' => session()->get('coupon')['total_amount']
            ]);
        } else {
            return response()->json([
                'total' => Cart::total()
            ]);
        }
    }

    public function couponRemove()
    {
        Session::forget('coupon');
        return response()->json(['success' => 'Coupon removed']);
    }
}
