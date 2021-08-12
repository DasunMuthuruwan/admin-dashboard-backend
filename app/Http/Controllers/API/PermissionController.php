<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{

          /**
         * @OA\Get(
         *      path="/permissions",
         *      security={{"bearerAuth":{}}},
         *      tags={"Permissions"},
         *      @OA\Response(
         *          response=200,
         *          description="Permission Collection"
         *      )
         * )
         */

    //
    public function index(){
        return PermissionResource::collection(Permission::all());
    }
}
