<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\isEmpty;

class RoleController extends Controller
{

    /**
     * @OA\Get(
     *      path="/roles",
     *      security={{"bearerAuth":{}}},
     *      tags={"Roles"},
     *      @OA\Response(
     *          response=200,
     *          description="Roles Collection"
     *      )
     * )
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('view', 'roles');

        return response()->json([
            'role' => RoleResource::collection(Role::all())
        ], 200);
    }



    /**
     * @OA\Post(
     *      path="/roles",
     *      security={{"bearerAuth":{}}},
     *      tags={"Roles"},
     *      description="Store Role data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/RoleRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Role",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      )
     * )
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {

        Gate::authorize('edit', 'roles');

        $permissions = $request->input('per');

        if ($permissions) {

            $role = Role::create($request->only('name'));

            foreach ($permissions as $permission_id) {
                $role->permissions()->attach($permission_id);
            }

            return response()->json([
                'status' => 1,
                'message' => 'Role created successfully'
                // 'role' => new RoleResource($role)
            ], Response::HTTP_CREATED);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Role is not created'
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Get(
     *      path="/roles/{id}",
     *      security={{"bearerAuth":{}}},
     *      tags={"Roles"},
     *      @OA\Response(
     *          response=200,
     *          description="Get Single Role Details"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="Role ID",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      )
     * )
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Gate::authorize('view', 'roles');

        return response()->json([
            'role' => new RoleResource(Role::find($id))
        ]);
    }

    /**
     * @OA\Put(
     *      path="/roles/{id}",
     *      security={{"bearerAuth":{}}},
     *      description="Update Role data",
     *      tags={"Roles"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/RoleRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Role Updated Successfully",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Role ID",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *   )
     * )
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        Gate::authorize('edit', 'roles');
        $role = Role::find($id);
        $permissions = $request->input('per');
        if ($permissions) {

            // Firstly Delete role_id with the permission id
            $role->permissions()->detach();
            // Only update roles, when have roles permissions
            $role->update($request->only('name'));

            foreach ($permissions as $permission_id) {
                // After attach roles permissions
                $role->permissions()->attach($permission_id);
            }
            return response()->json([
                'status' => 1,
                'message' => 'Role updated successfully'
            ], Response::HTTP_ACCEPTED);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Role is not updated'
        ], Response::HTTP_NOT_FOUND);
    }


    // Delete Role Details

    /**
     * @OA\Delete(
     *      path="/roles/{id}",
     *      security={{"bearerAuth":{}}},
     *      tags={"Roles"},
     *      description="Delete Role data",
     *      @OA\Response(
     *          response=200,
     *          description="Role deleted successfully",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="Role ID",
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *   )
     * )
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('edit', 'roles');
        $role = Role::find($id);

        if (DB::table('roles')->where('id', $id)->exists()) {
            $role->permissions()->detach();
            $role->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Role deleted successfully'
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Role ID not Found'
        ]);
    }
}
