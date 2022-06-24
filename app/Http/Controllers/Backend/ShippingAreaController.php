<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ShippingAreaController extends Controller
{
    public function provinceView()
    {
        $provinces = Province::orderBy('id', 'DESC')->get();
        return view('backend.ship.province.view_province', compact('provinces'));
    }

    public function provinceStore(Request $request)
    {
        $request->validate([
            'province_name' => 'required'
        ]);

        Province::insert([
            'province_name' => $request->province_name,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Province Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function provinceEdit($id)
    {
        $province = Province::findOrFail($id);
        return view('backend.ship.province.province_edit', compact('province'));
    }

    public function provinceUpdate(Request $request)
    {
        $provinceId = $request->id;
        Province::findOrFail($provinceId)->update([
            'province_name' => $request->province_name
        ]);

        $notification = array(
            'message' => 'Province Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('manage-province')->with($notification);
    }

    public function provinceDelete($id)
    {
        Province::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Province Deleted Successfully',
            'alert-type' => 'warning'
        );

        return redirect()->back()->with($notification);
    }

    public function districtView()
    {
        $provinces = Province::orderBy('province_name', 'ASC')->get();
        $districts = District::orderBy('id', 'DESC')->get();
        return view('backend.ship.district.view_district', compact('provinces', 'districts'));
    }
}
