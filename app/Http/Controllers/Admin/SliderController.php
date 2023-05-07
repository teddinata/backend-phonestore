<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * index
     *
     */
    public function index()
    {
        $sliders = Slider::latest()->paginate(5);
        return view ('admin.slider.index', compact('sliders'));
    }

      /**
     * store
     *
     * @param mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        // $this->validate($request. [
        //     'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
        //     'link'  => 'required'
        // ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/sliders', $image->hashName());

        //Save to DB
        $slider = Slider::create([
            'image' => $image->hashName(),
            'link'  => $request->link
        ]);

        if($slider){
            //redirect dengan pesan sukses
            return redirect()->route('admin.slider.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else{
            return redirect()->route('admin.slider.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        //cari data slider by ID
        $slider = Slider::findOrFail($id);

        //hapus file gambar slider dari server
        $image = Storage::disk('local')->delete('public/sliders/'.$slider->image);

        //hapus data slider dari database
        $slider->delete();

        if($slider){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
