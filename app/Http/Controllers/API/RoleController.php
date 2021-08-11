<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
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
        $permissions = $request->input('permissions');
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
        $role->permissions()->detach();
        $role->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Role deleted successfully'
        ]);
    }
}
