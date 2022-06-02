<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryView()
    {
        $category = Category::latest()->get();
        return view('backend.category.category_view', compact('category'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'category_name_en' => 'required',
            'category_name_vn' => 'required',
            'category_icon' => 'required'
        ], [
            'category_name_en.required' => 'Input Category English Name',
            'category_name_vn.required' => 'Input Category VN Name'
        ]);

        Category::insert([
            'category_name_en' => $request->category_name_en,
            'category_name_vn' => $request->category_name_vn,
            'category_slug_en' => strtolower(str_replace(' ', '-', $request->category_name_en)),
            'category_slug_vn' => strtolower(str_replace(' ', '-', $request->category_name_vn)),
            'category_icon' => $request->category_icon,
        ]);

        $notification = array(
            'message' => 'Brand Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function categoryEdit($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.category.category_edit', compact('category'));
    }

    public function categoryUpdate(Request $request)
    {
        $categoryId = $request->id;

        Category::findOrFail($categoryId)->update([
            'category_name_en' => $request->category_name_en,
            'category_name_vn' => $request->category_name_vn,
            'category_slug_en' => strtolower(str_replace(' ', '-', $request->category_name_en)),
            'category_slug_vn' => strtolower(str_replace(' ', '-', $request->category_name_vn)),
            'category_icon' => $request->category_icon
        ]);

        $notification = array(
            'message' => 'Category Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('all.category')->with($notification);

    }

    public function categoryDelete($id) {
        Category::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'warning'
        );

        return redirect()->back()->with($notification);
    }
}
