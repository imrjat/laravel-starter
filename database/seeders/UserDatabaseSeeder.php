<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class UserDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        Permission::firstOrCreate(['name' => 'view_users', 'label' => 'View Users', 'module' => 'Users']);
        Permission::firstOrCreate(['name' => 'view_users_profiles', 'label' => 'View Users Profiles', 'module' => 'Users']);
        Permission::firstOrCreate(['name' => 'view_users_activity', 'label' => 'View Users Activity', 'module' => 'Users']);
        Permission::firstOrCreate(['name' => 'add_users', 'label' => 'Add Users', 'module' => 'Users']);
        Permission::firstOrCreate(['name' => 'edit_users', 'label' => 'Edit Users', 'module' => 'Users']);
        Permission::firstOrCreate(['name' => 'edit_own_account', 'label' => 'Edit Own Account', 'module' => 'Users']);
        Permission::firstOrCreate(['name' => 'delete_users', 'label' => 'Delete Users', 'module' => 'Users']);

        Permission::firstOrCreate(['name' => 'view_regions', 'label' => 'View Regions', 'module' => 'Regions']);
        Permission::firstOrCreate(['name' => 'add_regions', 'label' => 'Add Regions', 'module' => 'Regions']);
        Permission::firstOrCreate(['name' => 'edit_regions', 'label' => 'Edit Regions', 'module' => 'Regions']);
        Permission::firstOrCreate(['name' => 'delete_regions', 'label' => 'Delete Regions', 'module' => 'Regions']);

        Permission::firstOrCreate(['name' => 'view_posts', 'label' => 'View Regions', 'module' => 'Posts']);
        Permission::firstOrCreate(['name' => 'add_posts', 'label' => 'Add Posts', 'module' => 'Posts']);
        Permission::firstOrCreate(['name' => 'edit_posts', 'label' => 'Edit Posts', 'module' => 'Posts']);
        Permission::firstOrCreate(['name' => 'delete_posts', 'label' => 'Delete Posts', 'module' => 'Posts']);
    }
}
