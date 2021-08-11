<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    // GET ALL USERS DEATILS API - GET REQUEST
    public function index(){

        // get collection of data
        // return UserResource::collection(User::paginate());


        /**
         * @OA\Get(
         *      path="/users",
         *      security={{"bearerAuth":{}}},
         *      tags={"Users"},
         *      @OA\Response(
         *          response=200,
         *          description="User Collection"
         *      ),
         *      @OA\Parameter(
         *          name="page",
         *          description="Page Pagination",
         *          in="query",
         *          @OA\Schema(
         *              type="integer"
         *          )
         *      )
         * )
         */

        Gate::authorize('view', 'users');
        $users = User::latest()->paginate(5);
        return response()->json([
            'status' => 1,
            'message' => 'get all users data',
            'data' => UserResource::collection($users)
        ]);
    }

    // GET SINGLE USER BY ID

    /**
         * @OA\Get(
         *      path="/users/{id}",
         *      security={{"bearerAuth":{}}},
         *      tags={"Users"},
         *      @OA\Response(
         *          response=200,
         *          description="Get Single User Details"
         *      ),
         *      @OA\Parameter(
         *          name="id",
         *          description="User ID",
         *          in="path",
         *          @OA\Schema(
         *              type="integer"
         *          )
         *      )
         * )
         */

    public function show($id){
        Gate::authorize('view', 'users');
        // get single data
        // return new UserResource(User::find($id));

        $user = User::find($id);

        if(isEmpty($user)){
            // return response()->json([
            //     'status' => 1,
            //     'message' => 'get single user data',
            //     'data' => $user
            // ]);
            return new UserResource($user);
        }
        return response()->json([
            'status' => 0,
            'message' => 'user not found'
        ]);
    }

        /**
         * @OA\Post(
         *      path="/users",
         *      security={{"bearerAuth":{}}},
         *      tags={"Users"},
         *      description="Store User data",
         *      @OA\RequestBody(
         *          required=true,
         *          @OA\JsonContent(ref="#/components/schemas/UserCreateRequest")
         *      ),
         *      @OA\Response(
         *          response=200,
         *          description="User",
         *          @OA\MediaType(
         *              mediaType="application/json",
         *          )
         *      )
         * )
         */

    public function store(UserCreateRequest $request){

        Gate::authorize('edit', 'users');

        $user = User::create(
            $request->only('first_name','last_name','email','role_id')
            + ['password' => bcrypt('12345678')]
        );

        return response()->json([
            'status' => 1,
            'message' => "User created successfully"
        ],Response::HTTP_CREATED);
    }

    // Update User Details

    /**
         * @OA\Put(
         *      path="/users/{id}",
         *      security={{"bearerAuth":{}}},
         *      description="Update User data",
         *      tags={"Users"},
         *      @OA\RequestBody(
         *          required=true,
         *          @OA\JsonContent(ref="#/components/schemas/UserUpdateRequest")
         *      ),
         *      @OA\Response(
         *          response=200,
         *          description="User Update",
         *          @OA\MediaType(
         *              mediaType="application/json",
         *          )
         *      ),
         *
         *      @OA\Parameter(
         *          name="id",
         *          description="User ID",
         *          in="path",
         *          @OA\Schema(
         *              type="integer"
         *          )
         *   )
         * )
         */

    public function update(UserUpdateRequest $request, $id){

        Gate::authorize('edit', 'users');

        $user = User::find($id);
        $user->update($request->only('first_name','last_name','email','role_id'));

        return response()->json([
            'status' => 1,
            'message' => 'User updated successfully'
        ],Response::HTTP_ACCEPTED);
    }

    // Delete User Details

        /**
         * @OA\Delete(
         *      path="/users/{id}",
         *      security={{"bearerAuth":{}}},
         *      tags={"Users"},
         *      description="Delete User data",
         *      @OA\Response(
         *          response=200,
         *          description="User Delete",
         *          @OA\MediaType(
         *              mediaType="application/json",
         *          )
         *      ),
         *
         *      @OA\Parameter(
         *          name="id",
         *          description="User ID",
         *          in="path",
         *          @OA\Schema(
         *              type="integer"
         *          )
         *   )
         * )
         */

    public function destroy($id){

        Gate::authorize('edit', 'users');

        // $user = User::find($id);
        // $user->delete();
        User::destroy($id);
        return response()->json([
            'status' => 1,
            'message'=> 'User deleted successfully'
        ]);

    }

    // GET USER PROFILE API

    /**
         * @OA\Get(
         *      path="/user",
         *      tags={"Profile"},
         *      security={{"bearerAuth":{}}},
         *      @OA\Response(
         *          response=200,
         *          description="User Profile"
         *      )
         * )
         */

    public function profile(){
        $user = Auth::user();

        return (new UserResource($user))->additional([
            'data' => [
                'permissions' => $user->permissions()
            ]
        ]);
    }

    // UPDATE USER INFORMATION

    /**
         * @OA\Put(
         *      path="/users/info",
         *      security={{"bearerAuth":{}}},
         *      tags={"Profile"},
         *      description="Update Authenticated User Info",
         *      @OA\RequestBody(
         *          required=true,
         *          @OA\JsonContent(ref="#/components/schemas/UpdateInfoRequest")
         *      ),
         *      @OA\Response(
         *          response=200,
         *          description="Authenticated User Update",
         *          @OA\MediaType(
         *              mediaType="application/json",
         *          )
         *      )
         * )
         */

    public function updateInfo(UpdateInfoRequest $request){
        $user = Auth::user();
        $user->update($request->only('first_name','last_name','email'));

        return response()->json([
            'status' => 1,
            'message' => "User info updated successfully",
        ],Response::HTTP_ACCEPTED);
    }

    // UPDATE USER PASSWORD

    /**
         * @OA\Put(
         *      path="/users/password",
         *      security={{"bearerAuth":{}}},
         *      tags={"Profile"},
         *      description="Update Authenticated User Passowrd",
         *      @OA\RequestBody(
         *          required=true,
         *          @OA\JsonContent(ref="#/components/schemas/UpdatePasswordRequest")
         *      ),
         *      @OA\Response(
         *          response=200,
         *          description="Authenticated User Passowrd Update",
         *          @OA\MediaType(
         *              mediaType="application/json",
         *          )
         *      )
         * )
         */

    public function updatePassword(UpdatePasswordRequest $request){
        $user = Auth::user();
        $user->update([
            'password' => bcrypt($request->input('password'))
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'User password updated successfully'
        ],Response::HTTP_ACCEPTED);
    }
}
