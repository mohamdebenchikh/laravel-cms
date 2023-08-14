<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate(['image'=>'required|image|mimes:jpg,jpeg,png']);
        $path = Storage::disk('public')->put('/images',$request->image);
        $url = Storage::url($path);
        return response()->json(['url'=>$url]);
    }

    public function delete(Request $request)
    {

    }
}
