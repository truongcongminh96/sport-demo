<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartPageController extends Controller
{
    public function myCart()
    {
        return view('frontend.wishlist.view_mycart');
    }

    public function getCartProduct()
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

    public function removeCartProduct($rowId)
    {
        Cart::remove($rowId);
        if (Session::has('coupon')) {
            Session::forget('coupon');
        }

        return response()->json(['success' => 'Product removed']);
    }

    public function cartIncrement($rowId)
    {
        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty + 1);

        if (Session::has('coupon')) {
            $couponName = Session::get('coupon')['coupon_name'];
            $coupon = Coupon::where(['coupon_name' => $couponName])->first();

            Session::put('coupon', [
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => ((int)Cart::total()) * $coupon->coupon_discount / 100,
                'total_amount' => Cart::total() - ((int)Cart::total()) * $coupon->coupon_discount / 100
            ]);
        }

        return response()->json('increment');
    }

    public function cartDecrement($rowId)
    {
        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty - 1);

        if (Session::has('coupon')) {
            $couponName = Session::get('coupon')['coupon_name'];
            $coupon = Coupon::where(['coupon_name' => $couponName])->first();

            Session::put('coupon', [
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => ((int)Cart::total()) * $coupon->coupon_discount / 100,
                'total_amount' => Cart::total() - ((int)Cart::total()) * $coupon->coupon_discount / 100
            ]);
        }

        return response()->json('decrement');
    }
}
