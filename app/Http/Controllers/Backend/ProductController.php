<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MultiImage;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function addProduct()
    {
        $categories = Category::latest()->get();
        $brands = Brand::latest()->get();
        return view('backend.product.product_add', compact('categories', 'brands'));
    }

    public function storeProduct(Request $request)
    {
        $image = $request->file('product_thumbnail');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(917, 1000)->save('upload/products/thumbnail/' . $name_gen);
        $save_url = 'upload/products/thumbnail/' . $name_gen;

        $productId = Product::insertGetId([
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'subsubcategory_id' => $request->subsubcategory_id,
            'product_name_en' => $request->product_name_en,
            'product_name_vn' => $request->product_name_vn,
            'product_slug_en' => strtolower(str_replace(' ', '-', $request->product_name_en)),
            'product_slug_vn' => str_replace(' ', '-', $request->product_name_vn),
            'product_code' => $request->product_code,

            'product_qty' => $request->product_qty,
            'product_tags_en' => $request->product_tags_en,
            'product_tags_vn' => $request->product_tags_vn,
            'product_size_en' => $request->product_size_en,
            'product_size_vn' => $request->product_size_vn,
            'product_color_en' => $request->product_color_en,
            'product_color_vn' => $request->product_color_vn,

            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'short_description_en' => $request->short_description_en,
            'short_description_vn' => $request->short_description_vn,
            'long_description_en' => $request->long_description_en,
            'long_description_vn' => $request->long_description_vn,

            'hot_deals' => $request->hot_deals,
            'featured' => $request->featured,
            'special_offer' => $request->special_offer,
            'special_deals' => $request->special_deals,

            'product_thumbnail' => $save_url,
            'status' => 1,
            'created_at' => Carbon::now()
        ]);

        //////// Multiple Image Upload Start ///////////
        $images = $request->file('multi_images');
        foreach ($images as $image) {
            $make_name = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(917, 1000)->save('upload/products/multi-image/' . $make_name);
            $uploadPath = 'upload/products/multi-image/' . $make_name;

            MultiImage::insert([
                'product_id' => $productId,
                'photo_name' => $uploadPath,
                'created_at' => Carbon::now()
            ]);
        }

        $notification = array(
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('manage-product')->with($notification);
    }

    public function manageProduct()
    {
        $products = Product::latest()->get();
        return view('backend.product.product_view', compact('products'));
    }

    public function editProduct($id)
    {
        $multiImages = MultiImage::where('product_id', $id)->get();

        $categories = Category::latest()->get();
        $brands = Brand::latest()->get();
        $subCategory = SubCategory::latest()->get();
        $subSubCategory = SubSubCategory::latest()->get();
        $products = Product::findOrFail($id);

        return view('backend.product.product_edit', compact('categories', 'brands', 'subCategory', 'subSubCategory', 'products', 'multiImages'));
    }

    public function productDataUpdate(Request $request)
    {
        $productId = $request->id;

        Product::findOrFail($productId)->update([
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'subsubcategory_id' => $request->subsubcategory_id,
            'product_name_en' => $request->product_name_en,
            'product_name_vn' => $request->product_name_vn,
            'product_slug_en' => strtolower(str_replace(' ', '-', $request->product_name_en)),
            'product_slug_vn' => str_replace(' ', '-', $request->product_name_vn),
            'product_code' => $request->product_code,

            'product_qty' => $request->product_qty,
            'product_tags_en' => $request->product_tags_en,
            'product_tags_vn' => $request->product_tags_vn,
            'product_size_en' => $request->product_size_en,
            'product_size_vn' => $request->product_size_vn,
            'product_color_en' => $request->product_color_en,
            'product_color_vn' => $request->product_color_vn,

            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'short_description_en' => $request->short_description_en,
            'short_description_vn' => $request->short_description_vn,
            'long_description_en' => $request->long_description_en,
            'long_description_vn' => $request->long_description_vn,

            'hot_deals' => $request->hot_deals,
            'featured' => $request->featured,
            'special_offer' => $request->special_offer,
            'special_deals' => $request->special_deals,
            'status' => 1,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Product Update Without Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('manage-product')->with($notification);
    }

    public function multiImageDataUpdate(Request $request)
    {
        $images = $request->multi_images;

        foreach ($images as $id => $image) {
            $imageDelete = MultiImage::findOrFail($id);
            @unlink($imageDelete->photo_name);

            $make_name = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(917, 1000)->save('upload/products/multi-image/' . $make_name);
            $uploadPath = 'upload/products/multi-image/' . $make_name;

            MultiImage::where('id', $id)->update([
                'photo_name' => $uploadPath,
                'updated_at' => Carbon::now()
            ]);
        }

        $notification = array(
            'message' => 'Product Update Multiple Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function thumbnailImageDataUpdate(Request $request)
    {
        $productId = $request->id;
        $oldImage = $request->old_image;
        @unlink($oldImage);

        $image = $request->file('product_thumbnail');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(917, 1000)->save('upload/products/thumbnail/' . $name_gen);
        $save_url = 'upload/products/thumbnail/' . $name_gen;

        Product::findOrFail($productId)->update([
            'product_thumbnail' => $save_url,
            'updated_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Product Update Thumbnail Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function multiImageDelete($id)
    {
        $oldImage = MultiImage::findOrFail($id);
        @unlink($oldImage->photo_name);

        MultiImage::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Product Delete Image Successfully',
            'alert-type' => 'warning'
        );

        return redirect()->back()->with($notification);
    }

    public function productInactive($id)
    {
        Product::findOrFail($id)->update([
            'status' => 0
        ]);

        $notification = array(
            'message' => 'Product Inactive',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function productActive($id)
    {
        Product::findOrFail($id)->update([
            'status' => 1
        ]);

        $notification = array(
            'message' => 'Product Active',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function productDelete($id)
    {
        $product = Product::findOrFail($id);
        @unlink($product->product_thumbnail);
        Product::findOrFail($id)->delete();

        $images = MultiImage::where('product_id', $id)->get();
        foreach ($images as $image) {
            @unlink($image->photo_name);
            MultiImage::where('product_id', $id)->delete();
        }

        $notification = array(
            'message' => 'Product Deleted!',
            'alert-type' => 'warning'
        );

        return redirect()->back()->with($notification);
    }
}
