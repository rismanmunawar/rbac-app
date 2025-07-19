<?php

namespace App\Livewire\MasterData\Role;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    use WithPagination;

    public $permissions = [];      // List semua permission
    public $selectedPermissions = []; // Checkbox terpilih
    public $name, $roleId;
    public $search = '';
    public $modalOpen = false;
    public array $selectAll = [];

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->permissions = Permission::all()
            ->groupBy(function ($item) {
                return explode('.', $item->name)[0]; // Ambil modul dari "modul.action"
            })
            ->map(function ($group) {
                return $group->pluck('name');
            })
            ->toArray();
    }

    public function render()
    {
        foreach ($this->permissions as $module => $perms) {
            $this->selectAll[$module] = !array_diff($perms, $this->selectedPermissions);
        }

        return view('livewire.master-data.role.index', [
            'roles' => Role::with('permissions')
                ->where('name', 'like', '%' . $this->search . '%')
                ->orderBy('name')
                ->paginate(10),
        ]);
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'roleId', 'selectedPermissions']);
        $this->modalOpen = true;

        if ($id) {
            $role = Role::findOrFail($id);
            $this->roleId = $role->id;
            $this->name = $role->name;
            $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        }
    }

    public function closeModal()
    {
        $this->modalOpen = false;
    }

    public function save()
    {
        $this->validate();

        $role = $this->roleId
            ? Role::findOrFail($this->roleId)->update(['name' => $this->name])
            : $role = Role::create(['name' => $this->name]);

        $role = $this->roleId ? Role::find($this->roleId) : $role;

        // Sync permissions
        $role->syncPermissions($this->selectedPermissions);

        $this->dispatch('showSuccess', $this->roleId ? 'Role diperbarui.' : 'Role ditambahkan.');
        $this->closeModal();
    }

    public function delete($id)
    {
        Role::find($id)?->delete();
        $this->dispatch('showSuccess', 'Role dihapus.');
    }

    public function toggleSelectAll($module)
    {
        if (!isset($this->permissions[$module])) return;

        $modulePermissions = $this->permissions[$module];

        if ($this->selectAll[$module] ?? false) {
            // Pilih semua permission dalam modul
            $this->selectedPermissions = array_unique(array_merge($this->selectedPermissions, $modulePermissions));
        } else {
            // Hapus permission dari modul tersebut
            $this->selectedPermissions = array_filter(
                $this->selectedPermissions,
                fn($perm) => !in_array($perm, $modulePermissions)
            );
        }
    }
}