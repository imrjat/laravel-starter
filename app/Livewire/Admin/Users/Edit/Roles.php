<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Users\Edit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Roles extends Component
{
    public User $user;

    /**
     * @var array<int>
     */
    public array $roleSelections = [];

    public function mount(): void
    {
        $this->roleSelections = $this->user->roles->pluck('id')->toArray();
    }

    public function render(): View
    {
        $roles = Role::where('tenant_id', auth()->user()->tenant_id)->orderby('name')->get();

        return view('livewire.admin.users.edit.roles', compact('roles'))->layout('layouts.app');
    }

    public function update(): bool
    {
        $role = Role::where('tenant_id', auth()->user()->tenant_id)
            ->where('name', 'admin')
            ->firstOrFail();

        //if admin role is not in array
        if (! in_array(needle: $role->id, haystack: $this->roleSelections, strict: true)) {
            $adminRolesCount = User::role('admin')->count();

            //when there is only 1 admin role alert user and stop
            if ($adminRolesCount === 1 && $this->user->hasRole('admin')) {
                flash('there must be at least one admin user!')->error();

                return false;
            }

            //@codeCoverageIgnoreStart
            $this->syncRoles();

            return false;
            //@codeCoverageIgnoreEnd
        }

        $this->syncRoles();

        return true;
    }

    protected function syncRoles(): void
    {
        //@phpstan-ignore-next-line
        $rolesWithTenant = collect($this->roleSelections)->map(function (string $roleId) {
            return [
                'role_id' => $roleId,
                'tenant_id' => auth()->user()->tenant_id,
                'model_type' => \App\Models\User::class,
                'model_id' => $this->user->id,
            ];
        })->toArray();

        $this->user->roles()->sync($rolesWithTenant);

        add_user_log([
            'title' => 'updated '.$this->user->name."'s roles",
            'reference_id' => $this->user->id,
            'link' => route('admin.users.edit', ['user' => $this->user->id]),
            'section' => 'Users',
            'type' => 'Update',
        ]);

        flash('Roles Updated!')->success();
    }
}
