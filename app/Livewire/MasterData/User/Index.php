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
    public bool $logModal = false;
    public array $logDetails = [];
    protected $paginationTheme = 'tailwind';
    public $showUserDetailModal = false;
    public $selectedUserDetail;
    public $lastUserLog;
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
            'is_active'
        ]);
    }

    public function save()
    {
        $this->validate();

        $isNew = !$this->userId;
        $user = $isNew ? new User : User::findOrFail($this->userId);
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
            'User ' . ($isNew ? 'ditambahkan' : 'diperbarui') . ' ID ' . $user->id,
            [
                // 'before' => $oldData, // Uncomment jika butuh log perubahan detail
                // 'after' => $user->toArray(),
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

        foreach ($users as $user) {
            $lastLog = ActivityLog::where('subject_type', User::class)
                ->where('subject_id', $user->id)
                ->latest()
                ->first();

            $user->modified_by = optional($lastLog?->causer)->name;
            $user->modified_at = optional($lastLog)->created_at;
        }

        return view('livewire.master-data.user.index', compact('users'));
    }

    // Optional: Uncomment jika nanti butuh lihat detail log
    // public function showLogDetail($userId)
    // {
    //     $lastLog = ActivityLog::where('subject_type', User::class)
    //         ->where('subject_id', $userId)
    //         ->latest()
    //         ->first();

    //     if ($lastLog) {
    //         $this->logDetails = [
    //             'created_at' => $lastLog->created_at->format('d M Y H:i'),
    //             'causer'     => optional($lastLog->causer)->name,
    //             'action'     => $lastLog->action,
    //             'changes'    => $this->diffChanges($lastLog->properties),
    //         ];
    //         $this->logModal = true;
    //     }
    // }

    // private function diffChanges($properties)
    // {
    //     $before = $properties['before'] ?? [];
    //     $after = $properties['after'] ?? [];

    //     $changes = [];
    //     foreach ($after as $key => $value) {
    //         $old = $before[$key] ?? null;
    //         if ($old != $value) {
    //             $changes[$key] = [
    //                 'before' => $old ?? '',
    //                 'after' => $value ?? '',
    //             ];
    //         }
    //     }

    //     return $changes;
    // }

    public function showUserDetail($id)
    {
        $this->selectedUserDetail = \App\Models\User::with('roles')->findOrFail($id);

        $this->lastUserLog = \App\Models\ActivityLog::where('subject_type', \App\Models\User::class)
            ->where('subject_id', $id)
            ->where('action', 'like', 'update_user%')
            ->latest()
            ->first();

        $this->showUserDetailModal = true;
    }
}
