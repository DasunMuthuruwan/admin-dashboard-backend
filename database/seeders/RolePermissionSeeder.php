<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $admin = Role::whereName('admin')->first();
        $permissions = Permission::all();

        foreach($permissions as $permission){
            $permission->roles()->attach($admin);
        }

        $editor = Role::whereName('editor')->first();

        foreach($permissions as $permission){
            if(!in_array($permission->name,['edit_roles'])){
                $permission->roles()->attach($editor);
            }
        }

        $viewer = Role::whereName('viewer')->first();
        $viwerRole = [
            'view_products', 'view_users', 'view_roles', 'view_orders'
        ];

        foreach($permissions as $permission){
            if(in_array($permission->name,$viwerRole)){
                $permission->roles()->attach($viewer);
            }
        }
    }
}
