<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    /**
     * @OA\Post(
     *      path="/upload",
     *      security={{"bearerAuth":{}}},
     *      tags={"Images"},
     *      description="Store Image",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  allOf={
     *                      @OA\Schema(ref="#/components/schemas/ImageUploadRequest"),
     *                      @OA\Schema(
     *                          @OA\Property(
     *                              description="File to Upload",
     *                              type="string",
     *                              format="binary",
     *                              title="image",
     *                              )
     *                          )
     *                      }
     *              )
     *          )
     *
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Image Uploaded successfully",
     *      )
     * )
     */
    //
    public function upload(ImageUploadRequest $request)
    {
        if ($request->hasFile('image')) {
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
        return response()->json([
            'status' => 0,
            'error' => 'image not found'
        ]);
    }
}
