<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
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
}
