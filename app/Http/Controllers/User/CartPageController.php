<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

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

        return response()->json(['success' => 'Product removed']);
    }

    public function cartIncrement($rowId)
    {
        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty + 1);

        return response()->json('increment');
    }

    public function cartDecrement($rowId)
    {
        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty - 1);

        return response()->json('decrement');
    }
}
