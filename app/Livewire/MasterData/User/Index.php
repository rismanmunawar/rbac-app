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
    public $showUserDetailModal = false;
    public $selectedUserDetail;
    public $lastUserLog;
    public array $directPermissions = [];
    public array $rolePermissions = [];

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

        $user->syncPermissions([]); // Clear old direct permissions
        $user->syncRoles([$this->role]);

        AuditHelper::log(
            $isNew ? 'create_user' : 'update_user',
            'User ' . ($isNew ? 'ditambahkan' : 'diperbarui') . ' ID ' . $user->id,
            [],
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
        $name = $user->name;
        $user->delete();
        $this->dispatch('deleted', "Role '{$name}' berhasil dihapus.");
    }
    protected $listeners = ['deleteConfirmed' => 'delete'];


    public function openPermissionModal($id)
    {
        $user = User::findOrFail($id);
        $user->load('roles.permissions', 'permissions');
        $this->userId = $user->id;

        $permissions = Permission::all();
        $grouped = [];

        foreach ($permissions as $perm) {
            $module = explode('.', $perm->name)[0] ?? 'other';
            $grouped[$module][] = $perm->name;
        }

        $this->allPermissions = $grouped;

        $this->rolePermissions = [];
        foreach ($user->roles as $role) {
            $this->rolePermissions = array_merge(
                $this->rolePermissions,
                $role->permissions->pluck('name')->toArray()
            );
        }

        $this->rolePermissions = array_unique($this->rolePermissions);
        $this->directPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

        // Combine both to show as checked in UI
        $this->userPermissions = array_unique(array_merge($this->rolePermissions, $this->directPermissions));

        $this->showPermissionModal = true;
    }

    public function savePermissions()
    {
        $user = User::findOrFail($this->userId);

        // Ambil semua yang dicentang, lalu ambil yang bukan dari role
        $selectedPermissions = is_array($this->userPermissions) ? $this->userPermissions : [];

        $directOnly = array_filter($selectedPermissions, function ($perm) {
            return !in_array($perm, $this->rolePermissions);
        });

        $user->syncPermissions($directOnly);

        AuditHelper::log(
            'update_user_permissions',
            'Memperbarui permission user ID ' . $user->id,
            ['permissions' => $directOnly],
            $user
        );

        $this->showPermissionModal = false;
        $this->dispatch('showSuccess', 'Permission user diperbarui.');
    }

    public function toggleModulePermissions($module, $checked)
    {
        $permissions = collect($this->allPermissions[$module] ?? []);

        $this->directPermissions = is_array($this->directPermissions) ? $this->directPermissions : [];
        $current = $this->directPermissions;

        if ($checked) {
            foreach ($permissions as $perm) {
                if (!in_array($perm, $this->rolePermissions) && !in_array($perm, $current)) {
                    $current[] = $perm;
                }
            }
        } else {
            $current = array_filter($current, function ($perm) use ($permissions) {
                return !in_array($perm, $permissions->toArray());
            });
        }

        $this->directPermissions = array_values($current);

        // Update untuk checkbox tampil
        $this->userPermissions = array_unique(array_merge($this->rolePermissions, $this->directPermissions));
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

    public function showUserDetail($id)
    {
        $this->selectedUserDetail = User::with('roles')->findOrFail($id);

        $this->lastUserLog = ActivityLog::where('subject_type', User::class)
            ->where('subject_id', $id)
            ->where('action', 'like', 'update_user%')
            ->latest()
            ->first();

        $this->showUserDetailModal = true;
    }

    public function toggleActive($userId)
    {
        $user = User::findOrFail($userId);
        $user->is_active = !$user->is_active;
        $user->save();

        AuditHelper::log(
            action: 'toggle_user_active',
            description: 'Toggle status aktif user',
            subject: $user,
        );

        $this->dispatch('showSuccess', 'Status user berhasil diperbarui.');
    }
}
