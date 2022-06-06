<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function subCategoryView()
    {
        $categories = Category::orderBy('category_name_en', 'ASC')->get();
        $subCategory = SubCategory::latest()->get();
        return view('backend.category.subcategory_view', compact('subCategory', 'categories'));
    }

    public function subCategoryStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'subcategory_name_en' => 'required',
            'subcategory_name_vn' => 'required',

        ], [
            'category_id' => 'Select Category',
            'subcategory_name_en.required' => 'Input SubCategory English Name',
            'subcategory_name_vn.required' => 'Input SubCategory VN Name'
        ]);

        SubCategory::insert([
            'category_id' => $request->category_id,
            'subcategory_name_en' => $request->subcategory_name_en,
            'subcategory_name_vn' => $request->subcategory_name_vn,
            'subcategory_slug_en' => strtolower(str_replace(' ', '-', $request->subcategory_name_en)),
            'subcategory_slug_vn' => strtolower(str_replace(' ', '-', $request->subcategory_name_vn)),

        ]);

        $notification = array(
            'message' => 'SubCategory Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function subCategoryEdit($id)
    {
        $categories = Category::orderBy('category_name_en', 'ASC')->get();
        $subCategory = SubCategory::findOrFail($id);
        return view('backend.category.subcategory_edit', compact('subCategory', 'categories'));
    }

    public function subCategoryUpdate(Request $request)
    {
        $subCategoryId = $request->id;

        SubCategory::findOrFail($subCategoryId)->update([
            'category_id' => $request->category_id,
            'subcategory_name_en' => $request->subcategory_name_en,
            'subcategory_name_vn' => $request->subcategory_name_vn,
            'subcategory_slug_en' => strtolower(str_replace(' ', '-', $request->subcategory_name_en)),
            'subcategory_slug_vn' => strtolower(str_replace(' ', '-', $request->subcategory_name_vn))
        ]);

        $notification = array(
            'message' => 'SubCategory Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('all.subcategory')->with($notification);

    }

    public function subCategoryDelete($id)
    {
        SubCategory::findOrFail($id)->delete();

        $notification = array(
            'message' => 'SubCategory Deleted Successfully',
            'alert-type' => 'warning'
        );

        return redirect()->back()->with($notification);
    }

    ///////////// That for SUB SUBCATEGORY /////////////
    public function subSubCategoryView()
    {
        $categories = Category::orderBy('category_name_en', 'ASC')->get();
        $subsubCategory = SubSubCategory::latest()->get();
        return view('backend.category.sub_subcategory_view', compact('subsubCategory', 'categories'));
    }

    public function getSubCategory($category_id)
    {
        $subCategory = SubCategory::where('category_id', $category_id)->orderBy('subcategory_name_en', 'ASC')->get();
        return json_encode($subCategory);
    }

    public function getSubSubCategory($subCategory_id)
    {
        $subSubCategory = SubSubCategory::where('subcategory_id', $subCategory_id)->orderBy('subsubcategory_name_en', 'ASC')->get();
        return json_encode($subSubCategory);
    }

    public function subSubCategoryStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'subsubcategory_name_en' => 'required',
            'subsubcategory_name_vn' => 'required',

        ], [
            'category_id' => 'Select Category',
            'subcategory_id' => 'Select SubCategory',
            'subsubcategory_name_en.required' => 'Input Sub->SubCategory English Name',
            'subsubcategory_name_vn.required' => 'Input Sub->SubCategory VN Name'
        ]);

        SubSubCategory::insert([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'subsubcategory_name_en' => $request->subsubcategory_name_en,
            'subsubcategory_name_vn' => $request->subsubcategory_name_vn,
            'subsubcategory_slug_en' => strtolower(str_replace(' ', '-', $request->subsubcategory_name_en)),
            'subsubcategory_slug_vn' => strtolower(str_replace(' ', '-', $request->subsubcategory_name_vn))
        ]);

        $notification = array(
            'message' => 'Sub->SubCategory Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function subSubCategoryEdit($id)
    {
        $categories = Category::orderBy('category_name_en', 'ASC')->get();
        $subCategory = SubCategory::orderBy('subcategory_name_en', 'ASC')->get();
        $subSubCategory = SubSubCategory::findOrFail($id);
        return view('backend.category.sub_subcategory_edit', compact('subCategory', 'categories', 'subSubCategory'));
    }

    public function subSubCategoryUpdate(Request $request)
    {
        $subSubCat = $request->id;

        SubSubCategory::findOrFail($subSubCat)->update([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'subsubcategory_name_en' => $request->subsubcategory_name_en,
            'subsubcategory_name_vn' => $request->subsubcategory_name_vn,
            'subsubcategory_slug_en' => strtolower(str_replace(' ', '-', $request->subsubcategory_name_en)),
            'subsubcategory_slug_vn' => strtolower(str_replace(' ', '-', $request->subsubcategory_name_vn))
        ]);

        $notification = array(
            'message' => 'Sub->SubCategory Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('all.subsubcategory')->with($notification);

    }

    public function subSubCategoryDelete($id)
    {
        SubSubCategory::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Sub->SubCategory Deleted Successfully',
            'alert-type' => 'warning'
        );

        return redirect()->back()->with($notification);
    }

}
