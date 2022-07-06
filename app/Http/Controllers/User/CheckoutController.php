<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Ward;
use Gloudemans\Shoppingcart\Facades\Cart;
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

    public function checkoutStore(Request $request)
    {
        $data = [];
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['post_code'] = $request->post_code ?? null;
        $data['province_id'] = $request->province_id;
        $data['district_id'] = $request->district_id;
        $data['ward_id'] = $request->ward_id;
        $data['notes'] = $request->notes;
        $cartTotal = Cart::total();

        if ($request->payment_method == 'stripe') {
            return view('frontend.payment.stripe', compact('data', 'cartTotal'));
        } else if ($request->payment_method == 'card') {
            return 'card';
        } else {
            return 'cash';
        }
    }
}
