<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BrandController extends Controller
{
    public function brandView()
    {
        $brands = Brand::latest()->get();
        return view('backend.brand.brand_view', compact('brands'));
    }

    public function brandStore(Request $request)
    {
        $request->validate([
            'brand_name_en' => 'required',
            'brand_name_vn' => 'required',
            'brand_image' => 'required'
        ], [
            'brand_name_en.required' => 'Input Brand English Name',
            'brand_name_vn.required' => 'Input Brand VN Name'
        ]);

        $image = $request->file('brand_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(300, 300)->save('upload/brand/' . $name_gen);
        $save_url = 'upload/brand/' . $name_gen;

        Brand::insert([
            'brand_name_en' => $request->brand_name_en,
            'brand_name_vn' => $request->brand_name_vn,
            'brand_slug_en' => strtolower(str_replace(' ', '-', $request->brand_name_en)),
            'brand_slug_vn' => str_replace(' ', '-',$request->brand_name_vn),
            'brand_image'   => $save_url,
        ]);

        $notification = array(
            'message' => 'Brand Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function brandEdit($id) {
        $brand = Brand::findOrFail($id);
        return view('backend.brand.brand_edit', compact('brand'));
    }

    public function brandUpdate(Request $request) {
        $brandId = $request->id;
        $oldImage = $request->old_image;

        if ($request->file('brand_image')) {
            @unlink($oldImage);
            $image = $request->file('brand_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300, 300)->save('upload/brand/' . $name_gen);
            $save_url = 'upload/brand/' . $name_gen;

            Brand::findOrFail($brandId)->update([
                'brand_name_en' => $request->brand_name_en,
                'brand_name_vn' => $request->brand_name_vn,
                'brand_slug_en' => strtolower(str_replace(' ', '-', $request->brand_name_en)),
                'brand_slug_vn' => str_replace(' ', '-',$request->brand_name_vn),
                'brand_image'   => $save_url
            ]);

            $notification = array(
                'message' => 'Brand Updated Successfully',
                'alert-type' => 'info'
            );

            return redirect()->route('all.brand')->with($notification);
        } else {
            Brand::findOrFail($brandId)->update([
                'brand_name_en' => $request->brand_name_en,
                'brand_name_vn' => $request->brand_name_vn,
                'brand_slug_en' => strtolower(str_replace(' ', '-', $request->brand_name_en)),
                'brand_slug_vn' => str_replace(' ', '-',$request->brand_name_vn)
            ]);

            $notification = array(
                'message' => 'Brand Updated Successfully',
                'alert-type' => 'info'
            );

            return redirect()->route('all.brand')->with($notification);
        }
    }

    public function brandDelete($id) {
        $brand = Brand::findOrFail($id);
        $img = $brand->brand_image;
        @unlink($img);

        Brand::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Brand Deleted Successfully',
            'alert-type' => 'warning'
        );

        return redirect()->back()->with($notification);
    }
}
