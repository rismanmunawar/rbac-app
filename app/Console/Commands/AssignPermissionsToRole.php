<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionsToRole extends Command
{
    protected $signature = 'permission:assign {role=super admin}';
    protected $description = 'Assign all permissions to a specific role (default: super admin)';

    public function handle()
    {
        $roleName = $this->argument('role');

        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            $this->error("Role '{$roleName}' not found.");
            return Command::FAILURE;
        }

        $permissions = Permission::pluck('name');

        if ($permissions->isEmpty()) {
            $this->warn('No permissions found to assign.');
            return Command::SUCCESS;
        }

        $role->syncPermissions($permissions);
        $this->info("Assigned " . $permissions->count() . " permissions to role '{$roleName}'.");

        return Command::SUCCESS;
    }
}