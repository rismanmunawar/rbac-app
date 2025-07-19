<?php

namespace App\Livewire\MasterData\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Helpers\AuditHelper;
use App\Models\ActivityLog;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $modalOpen = false;
    public $userId;
    public $name, $email, $password, $role, $is_active = true;
    public $roles = [];
    public $allPermissions = [];
    public $userPermissions = [];
    public $showPermissionModal = false;
    public $logModal = false;
    public $logDetails = [];

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => $this->userId ? 'nullable|min:6' : 'required|min:6',
            'role' => 'required|string|exists:roles,name',
        ];
    }

    public function mount()
    {
        $this->roles = Role::pluck('name')->toArray();
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'role', 'userId', 'is_active']);

        if ($id) {
            $user = User::findOrFail($id);
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->is_active = $user->is_active;
            $this->role = $user->roles->pluck('name')->first();
        }

        $this->modalOpen = true;
    }

    public function closeModal()
    {
        $this->reset([
            'modalOpen',
            'userId',
            'name',
            'email',
            'password',
            'role',
            'is_active',
        ]);
    }

    public function save()
    {
        $this->validate();

        $isNew = !$this->userId;

        $user = $isNew ? new User : User::findOrFail($this->userId);
        $oldData = $user->exists ? $user->toArray() : [];

        $user->name = $this->name;
        $user->email = $this->email;
        $user->is_active = $this->is_active;

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();
        $user->syncRoles([$this->role]);

        AuditHelper::log(
            $isNew ? 'create_user' : 'update_user',
            ($isNew ? 'Menambahkan' : 'Memperbarui') . ' user ID: ' . $user->id,
            [
                'before' => $isNew ? null : $oldData,
                'after' => $user->toArray()
            ],
            $user
        );

        $this->dispatch('showSuccess', $isNew ? 'User berhasil ditambahkan.' : 'User berhasil diperbarui.');
        $this->closeModal();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        AuditHelper::log(
            'delete_user',
            'Menghapus user ID ' . $user->id,
            ['data' => $user->toArray()],
            $user
        );

        $user->delete();

        $this->dispatch('showSuccess', 'User dihapus.');
    }

    public function openPermissionModal($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->allPermissions = Permission::pluck('name')->toArray();
        $this->userPermissions = $user->getPermissionNames()->toArray();
        $this->showPermissionModal = true;
    }

    public function savePermissions()
    {
        $user = User::findOrFail($this->userId);
        $user->syncPermissions($this->userPermissions);

        AuditHelper::log(
            'update_user_permissions',
            'Memperbarui permission user ID ' . $user->id,
            ['permissions' => $this->userPermissions],
            $user
        );

        $this->showPermissionModal = false;
        $this->dispatch('showSuccess', 'Permission user diperbarui.');
    }

    public function render()
    {
        $users = User::query()
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->with('roles')
            ->paginate(10);

        return view('livewire.master-data.user.index', compact('users'));
    }

    public function showLogDetail($userId)
    {
        $log = ActivityLog::where('subject_type', User::class)
            ->where('subject_id', $userId)
            ->where('action', 'like', 'update_user%')
            ->latest()
            ->first();

        if ($log) {
            $changes = [];
            foreach (($log->properties['after'] ?? []) as $key => $newValue) {
                $oldValue = $log->properties['before'][$key] ?? '';
                if ($oldValue != $newValue) {
                    $changes[$key] = ['before' => $oldValue, 'after' => $newValue];
                }
            }

            $this->logDetails = [
                'created_at' => $log->created_at->format('d M Y H:i'),
                'causer' => $log->causer?->name ?? '-',
                'action' => $log->action,
                'changes' => $changes,
            ];
        } else {
            $this->logDetails = null;
        }

        $this->logModal = true;
    }
}
