<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function getDistrictsAjax($provinceId)
    {
        $districts = District::where(['province_id' => $provinceId])->orderBy('district_name', 'ASC')->get();
        return json_encode($districts);
    }

    public function getWardsAjax($districtId)
    {
        $wards = Ward::where(['district_id' => $districtId])->orderBy('ward_name', 'ASC')->get();
        return json_encode($wards);
    }

    public function checkoutStore(Request $request) {
        dd($request->all());
        $data = [];
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['post_code'] = $request->post_code ?? null;
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_name'] = $request->shipping_name;
    }
}
