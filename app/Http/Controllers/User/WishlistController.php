<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function viewWishlist()
    {
        return view('frontend.wishlist.view_wishlist');
    }

    public function getWishlistProduct()
    {
        $wishlist = Wishlist::with('product')->where(['user_id' => Auth::id()])->latest()->get();

        return response()->json($wishlist);
    }

    public function removeWishlistProduct($id)
    {
        Wishlist::where(['user_id' => Auth::id(), 'id' => $id])->delete();
        return response()->json(['success' => 'Successfully remove product in your wishlist']);
    }
}
