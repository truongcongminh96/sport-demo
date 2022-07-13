<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Seo;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
//    public function siteSetting() {
//
//    }

    public function seoSetting()
    {
        $seo = Seo::find(1);
        return view('backend.setting.seo_update', compact('seo'));
    }

    public function seoSettingUpdate(Request $request)
    {
        $seoId = $request->id;

        Seo::findOrFail($seoId)->update([
            'meta_title' => $request->meta_title,
            'meta_author' => $request->meta_author,
            'meta_keyword' => $request->meta_keyword,
            'meta_description' => $request->meta_description,
            'google_analytics' => $request->google_analytics
        ]);

        $notification = array(
            'message' => 'SEO Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
