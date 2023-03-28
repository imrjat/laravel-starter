<?php

namespace Tests;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function authenticate(string $role = 'admin', string $permissionName = ''): self
    {
        $user = $this->prepareUser($role);

        if ($permissionName) {
            Gate::define($permissionName, static function () {
                return true;
            });
        }

        return $this->actingAs($user);
    }

    protected function prepareUser($role): User
    {
        $this->prepareRole($role);

        $user = User::factory()->create();

        $tenant = Tenant::create([
            'owner_id'      => $user->id,
            'trial_ends_at' => now()->addDays(config('admintw.trail_days')),
        ]);

        TenantUser::create([
            'tenant_id' => $tenant->id,
            'user_id'   => $user->id,
        ]);

        $user->tenant_id = $tenant->id;
        $user->assignRole($role);
        $user->save();

        return $user;
    }

    protected function prepareRole($role): Role
    {
        return Role::firstOrCreate(['name' => $role, 'label' => ucwords($role)]);
    }

    protected function preparePermission($permission): Role
    {
        Permission::firstOrCreate(['name' => $permission]);
    }
}
