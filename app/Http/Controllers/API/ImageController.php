<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    //
    public function upload(ImageUploadRequest $request){
        $diskName = 'public';
        // create disk url
        $disk = Storage::disk($diskName);
        $path = $request->file('image')->store('ProductImages' . Auth::user()->id, $diskName);
        //set to disk url and path
        $url = $disk->url($path);

        return response()->json([
            'url' => $url
        ]);
    }
}
