<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MultiImage;
use App\Models\Product;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 1)->orderBy('id', 'DESC')->limit(6)->get();
        $sliders = Slider::where('status', 1)->orderBy('id', 'DESC')->limit(3)->get();
        $categories = Category::orderBy('id', 'ASC')->get();
        $featured = Product::where('featured', 1)->orderBy('id', 'DESC')->limit(6)->get();
        $hotDeals = Product::where('hot_deals', 1)->where('discount_price', '!=', NULL)->orderBy('id', 'DESC')->limit(3)->get();
        $specialOffer = Product::where('special_offer', 1)->orderBy('id', 'DESC')->limit(9)->get();
        $specialDeals = Product::where('special_deals', 1)->orderBy('id', 'DESC')->limit(9)->get();
        $skipCategory0 = Category::skip(0)->first();
        $skipProduct0 = Product::where('status', 1)->where('category_id', $skipCategory0->id)->orderBy('id', 'DESC')->get();
        $skipCategory1 = Category::skip(1)->first();
        $skipProduct1 = Product::where('status', 1)->where('category_id', $skipCategory1->id)->orderBy('id', 'DESC')->get();
        $skipBrand1 = Brand::skip(1)->first();
        $skipBrandProduct1 = Product::where('status', 1)->where('brand_id', $skipBrand1->id)->orderBy('id', 'DESC')->get();

        return view(
            'frontend.index',
            compact(
                'categories',
                'sliders',
                'products',
                'featured',
                'hotDeals',
                'specialOffer',
                'specialDeals',
                'skipCategory0',
                'skipProduct0',
                'skipCategory1',
                'skipProduct1',
                'skipBrand1',
                'skipBrandProduct1'
            )
        );
    }

    public function userLogout()
    {
//        Cart::destroy();
        Auth::logout();
        return redirect()->route('login');
    }

    public function userProfile()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        return view('frontend.profile.user_profile', compact('user'));
    }

    public function userProfileStore(Request $request)
    {
        $data = User::find(Auth::user()->id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        if ($request->file('profile_photo_path')) {
            $file = $request->file('profile_photo_path');
            @unlink(public_path('upload/user_images/' . $data->profile_photo_path));
            $fileName = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/user_images'), $fileName);
            $data['profile_photo_path'] = $fileName;
        }

        $data->save();

        $notification = array(
            'message' => 'User Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('dashboard')->with($notification);
    }

    public function userChangePassword()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        return view('frontend.profile.change_password', compact('user'));
    }

    public function userPasswordUpdate(Request $request)
    {
        $validateData = $request->validate([
            'oldpassword' => 'required',
            'password' => 'required|confirmed'
        ]);

        $hashedPassword = Auth::user()->password;

        if (Hash::check($request->oldpassword, $hashedPassword)) {
            $user = User::find(Auth::id());
            $user->password = Hash::make($request->password);
            $user->save();
            Auth::logout();
            return redirect()->route('user.logout');
        } else {
            return redirect()->back();
        }
    }

    public function productDetails($id, $slug)
    {
        $product = Product::findOrFail($id);

        $colorEn = $product->product_color_en;
        $productColorEn = explode(',', $colorEn);
        $colorVn = $product->product_color_vn;
        $productColorVn = explode(',', $colorVn);

        $sizeEn = $product->product_size_en;
        $productSizeEn = explode(',', $sizeEn);
        $sizeVn = $product->product_size_vn;
        $productSizeVn = explode(',', $sizeVn);

        $multiImage = MultiImage::where('product_id', $id)->get();

        $categoryId = $product->category_id;
        $relatedProduct = Product::where('category_id', $categoryId)->where('id', '!=', $id)->orderBy('id', 'DESC')->get();
        return view(
            'frontend.product.product_details',
            compact(
                'product',
                'multiImage',
                'productColorEn',
                'productColorVn',
                'productSizeEn',
                'productSizeVn',
                'relatedProduct'
            )
        );
    }

    public function tagWiseProduct($tag)
    {
        $products = Product::where('status', 1)
            ->where('product_tags_en', $tag)
            ->where('product_tags_vn', $tag)
            ->orderBy('id', 'DESC')
            ->paginate(3);

        $categories = Category::orderBy('id', 'ASC')->get();

        return view('frontend.tags.tags_view', compact('products', 'categories'));
    }

    public function subCategoryWiseProduct(Request $request, $subCategoryId, $slug)
    {
        $products = Product::where('status', 1)
            ->where('subcategory_id', $subCategoryId)
            ->orderBy('id', 'DESC')
            ->paginate(1);

        $categories = Category::orderBy('id', 'ASC')->get();
        $breadSubCategories = SubCategory::with('category')->where(['id' => $subCategoryId])->get();

        if ($request->ajax()) {
            $girdView = view('frontend.product.grid_view_product', compact('products'))->render();
            $listView = view('frontend.product.list_view_product', compact('products'))->render();
            return response()->json(['grid_view' => $girdView, 'list_view' => $listView]);
        }

        return view('frontend.product.subcategory_view', compact('products', 'categories', 'breadSubCategories'));
    }

    public function subSubCategoryWiseProduct($subSubCategoryId, $slug)
    {
        $products = Product::where('status', 1)
            ->where('subsubcategory_id', $subSubCategoryId)
            ->orderBy('id', 'DESC')
            ->paginate(6);

        $categories = Category::orderBy('id', 'ASC')->get();

        return view('frontend.product.sub_subcategory_view', compact('products', 'categories'));
    }

    public function productViewAjax($id)
    {
        $product = Product::with('category', 'brand')->findOrFail($id);

        $colorEn = $product->product_color_en;
        $productColorEn = explode(',', $colorEn);
//        $colorVn = $product->product_color_vn;
//        $productColorVn = explode(',', $colorVn);

        $sizeEn = $product->product_size_en;
        $productSizeEn = explode(',', $sizeEn);
//        $sizeVn = $product->product_size_vn;
//        $productSizeVn = explode(',', $sizeVn);

        return response()->json(array(
            'product' => $product,
            'color' => $productColorEn,
            'size' => $productSizeEn
        ));
    }

    public function productSearch(Request $request)
    {
        $request->validate([
            'search' => 'required'
        ]);
        $categories = Category::orderBy('category_name_en', 'ASC')->get();
        $products = Product::where('product_name_en', 'LIKE', "%$request->search%")->get();
        return view('frontend.product.search', compact('products', 'categories'));
    }

    public function productSearchAdvanced(Request $request)
    {
        $request->validate([
            'search' => 'required'
        ]);

        $products = Product::where('product_name_en', 'LIKE', "%$request->search%")->select('product_name_en', 'product_thumbnail', 'selling_price', 'id', 'product_slug_en')->get();
        return view('frontend.product.search_advanced', compact('products'));
    }
}
