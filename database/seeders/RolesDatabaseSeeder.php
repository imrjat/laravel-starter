<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class RolesDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        Role::firstOrCreate(['name' => 'admin', 'label' => 'Admin']);
        Role::firstOrCreate(['name' => 'user', 'label' => 'User']);
    }
}
