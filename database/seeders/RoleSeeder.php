<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'SuperAdmin',
                'guard_name' => 'web',
                'role_for' => 'Admin',
            ],
            [
                'name' => 'Admin',
                'guard_name' => 'web',
                'role_for' => 'Admin',
            ],
            [
                'name' => 'Member',
                'guard_name' => 'web',
                'role_for' => 'Admin',
            ],
           
        ];
        foreach($roles as $role){
            $role =  Role::create($role);
            if($role->name == 'SuperAdmin'){
                $role->syncPermissions(Permission::all());
            }
        }
    }
}
