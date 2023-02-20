<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class SentEmailsDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        Permission::firstOrCreate(['name' => 'view_sent_emails', 'label' => 'View Sent Emails', 'module' => 'SentEmails']);
    }
}
