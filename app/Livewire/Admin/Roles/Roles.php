<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Roles;

use App\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Roles')]
class Roles extends Component
{
    use WithPagination;

    public string $paginate = '';
    public string $query = '';
    public string $sortField = 'name';
    public bool $sortAsc = true;

    protected $listeners = ['refreshRoles' => '$refresh'];

    public function render(): View
    {
        abort_if_cannot('view_roles');

        return view('livewire.admin.roles.index');
    }

    public function builder(): Builder
    {
        return Role::where('tenant_id', auth()->user()->tenant_id)->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function roles(): LengthAwarePaginator
    {
        $query = $this->builder();

        if ($this->query) {
            $query->where('name', 'like', '%'.$this->query.'%');
        }

        return $query->paginate($this->paginate);
    }

    public function deleteRole(string $id): void
    {
        $this->builder()->findOrFail($id)->delete();

        $this->dispatch('close-modal');
    }
}