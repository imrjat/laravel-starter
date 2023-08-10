<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Roles;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Role')]
class Edit extends Component
{
    public Role|null $role = null;
    public string $label = '';
    public array $permissions = [];

    protected function rules(): array
    {
        return [
            'label' => [
                'required',
                'string',
                Rule::unique('roles')
                    ->where('tenant_id', $this->role->tenant_id) // Consider the current tenant
                    ->ignore($this->role->id), // Ignore the current role when updating
            ],
        ];
    }

    protected array $messages = [
        'label.required' => 'Role is required',
    ];

    /**
     * @throws ValidationException
     */
    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function mount(Role $role): void
    {
        $this->role = Role::where('tenant_id', auth()->user()->tenant_id)->where('id', $role->id)->firstOrFail();

        $this->label = $this->role->label ?? '';

        if (isset($this->role->permissions)) {
            foreach ($this->role->permissions as $perm) {
                $this->permissions[] = $perm->name;
            }
        }
    }

    public function render(): View
    {
        abort_if_cannot('edit_roles');

        $modules = Permission::select('module')->distinct()->orderBy('module')->pluck('module');

        return view('livewire.admin.roles.edit', compact('modules'));
    }

    public function update(): Redirector|RedirectResponse
    {
        $this->validate();

        $this->role->label = $this->label;
        $this->role->name = strtolower(str_replace(' ', '_', $this->label));

        if ($this->permissions) {
            $this->role->syncPermissions($this->permissions);
        }

        $this->role->save();

        add_user_log([
            'title' => 'updated role '.$this->label,
            'link' => route('admin.settings.roles.edit', ['role' => $this->role->id]),
            'reference_id' => $this->role->id,
            'section' => 'Roles',
            'type' => 'Update',
        ]);

        flash('Role updated')->success();

        return redirect()->route('admin.settings.roles.index');
    }
}
