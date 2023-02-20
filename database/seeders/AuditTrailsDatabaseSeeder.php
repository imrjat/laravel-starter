<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class AuditTrailsDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        Permission::firstOrCreate(['name' => 'view_audit_trails', 'label' => 'View Audit Trails', 'module' => 'Audit Trails']);
    }
}
