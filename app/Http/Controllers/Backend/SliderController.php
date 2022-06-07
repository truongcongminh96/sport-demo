<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class SliderController extends Controller
{
    public function sliderView()
    {
        $sliders = Slider::latest()->get();
        return view('backend.slider.slider_view', compact('sliders'));
    }

    public function sliderStore(Request $request)
    {
        $request->validate([
            'slider_image' => 'required'
        ], [
            'slider_image.required' => 'Select One Image'
        ]);

        $image = $request->file('slider_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(870, 370)->save('upload/slider/' . $name_gen);
        $save_url = 'upload/slider/' . $name_gen;

        Slider::insert([
            'title' => $request->title,
            'description' => $request->description,
            'slider_image' => $save_url
        ]);

        $notification = array(
            'message' => 'Slider Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function sliderEdit($id)
    {
        $sliders = Slider::findOrFail($id);
        return view('backend.slider.slider_edit', compact('sliders'));
    }

    public function sliderUpdate(Request $request)
    {
        $sliderID = $request->id;
        $oldImage = $request->old_image;

        if ($request->file('slider_image')) {
            @unlink($oldImage);
            $image = $request->file('slider_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(870, 370)->save('upload/slider/' . $name_gen);
            $save_url = 'upload/slider/' . $name_gen;

            Slider::findOrFail($sliderID)->update([
                'title' => $request->title,
                'description' => $request->description,
                'slider_image' => $save_url
            ]);

            $notification = array(
                'message' => 'Slider Updated Successfully',
                'alert-type' => 'info'
            );

            return redirect()->route('manage-slider')->with($notification);
        } else {
            Slider::findOrFail($sliderID)->update([
                'title' => $request->title,
                'description' => $request->description
            ]);

            $notification = array(
                'message' => 'Slider Updated Successfully',
                'alert-type' => 'info'
            );

            return redirect()->route('manage-slider')->with($notification);
        }
    }

    public function sliderDelete($id) {
        $slider = Slider::findOrFail($id);
        $img = $slider->slider_image;
        @unlink($img);

        Slider::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Slider Deleted Successfully',
            'alert-type' => 'warning'
        );

        return redirect()->back()->with($notification);
    }

    public function sliderInactive($id)
    {
        Slider::findOrFail($id)->update([
            'status' => 0
        ]);

        $notification = array(
            'message' => 'Slider Inactive',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function sliderActive($id)
    {
        Slider::findOrFail($id)->update([
            'status' => 1
        ]);

        $notification = array(
            'message' => 'Slider Active',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
