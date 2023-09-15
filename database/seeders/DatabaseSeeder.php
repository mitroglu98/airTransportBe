<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Roles
        $role1 = Role::firstOrCreate(['name' => 'operator1']);
        $role2 = Role::firstOrCreate(['name' => 'operator2']);
    
        // Permissions
        $view = Permission::firstOrCreate(['name' => 'view']);
        $manage = Permission::firstOrCreate(['name' => 'manage']);
    
        // Assign permissions to roles
        $role1->givePermissionTo($view);
        $role2->givePermissionTo($view, $manage);
    }
}
