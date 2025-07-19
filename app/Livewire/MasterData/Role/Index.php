<?php

namespace App\Livewire\MasterData\Role;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination;

    public $name, $roleId;
    public $search = '';
    public $modalOpen = false;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.master-data.role.index', compact('roles'));
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'roleId']);
        $this->modalOpen = true;

        if ($id) {
            $role = Role::findOrFail($id);
            $this->roleId = $role->id;
            $this->name = $role->name;
        }
    }

    public function closeModal()
    {
        $this->modalOpen = false;
    }

    public function save()
    {
        $this->validate();

        if ($this->roleId) {
            Role::find($this->roleId)->update(['name' => $this->name]);
        } else {
            Role::create(['name' => $this->name]);
        }

        $this->dispatch('showSuccess', $this->roleId ? 'Role diperbarui.' : 'Role ditambahkan.');
        $this->closeModal();
    }

    public function delete($id)
    {
        Role::find($id)?->delete();
        $this->dispatch('showSuccess', 'Role dihapus.');
    }
}
