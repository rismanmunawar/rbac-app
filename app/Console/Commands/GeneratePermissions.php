<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;

class GeneratePermissions extends Command
{
    protected $signature = 'permission:generate 
                            {--module=* : Nama modul yang ingin digenerate (jika kosong, semua model dipakai)} 
                            {--force : Hapus dan buat ulang permission jika sudah ada}';

    protected $description = 'Generate default CRUD permissions berdasarkan model di app/Models';

    protected $actions = ['create', 'read', 'update', 'delete'];

    public function handle(): void
    {
        $force = $this->option('force');
        $inputModules = $this->option('module');

        $this->info("📦 Mendeteksi model di folder app/Models...");

        $modelFiles = File::allFiles(app_path('Models'));

        foreach ($modelFiles as $file) {
            $className = $file->getFilenameWithoutExtension();

            $module = strtolower($className);

            // Jika user menentukan module tertentu
            if (!empty($inputModules) && !in_array($module, $inputModules)) {
                continue;
            }

            $this->generatePermissionsForModule($module, $force);
        }

        if (empty($modelFiles)) {
            $this->warn("⚠️ Tidak ada model ditemukan di app/Models.");
        } else {
            $this->info("✅ Selesai generate permission.");
        }
    }

    protected function generatePermissionsForModule(string $module, bool $force = false): void
    {
        $this->info("🔧 Generating permissions for: {$module}");

        foreach ($this->actions as $action) {
            $name = "{$module}.{$action}";

            if (Permission::where('name', $name)->exists()) {
                if ($force) {
                    Permission::where('name', $name)->delete();
                    Permission::create(['name' => $name]);
                    $this->warn("🔁 Regenerated: {$name}");
                } else {
                    $this->line("⏭️  Exists: {$name}");
                }
            } else {
                Permission::create(['name' => $name]);
                $this->info("✅ Created: {$name}");
            }
        }
    }
}