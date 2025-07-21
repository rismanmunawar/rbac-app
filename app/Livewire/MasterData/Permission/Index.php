<?php

namespace App\Livewire\MasterData\Permission;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\File;

class Index extends Component
{
    use WithPagination;
    public $availableModels = [];
    public $search = '';
    public $selectedIds = [];
    public $selectAll = false;
    public $moduleName = '';
    protected $listeners = ['deleteConfirmed' => 'delete'];
    public $showAddModal = false;
    public $manualName;
    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->loadAvailableModels();
    }
    protected function rules()
    {
        return [
            'moduleName' => 'required|string|max:100',
        ];
    }

    public function updatedSelectedIds()
    {
        $this->selectAll = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedIds = Permission::query()
                ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->pluck('id')
                ->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function render()
    {
        $query = Permission::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name');

        $paginated = $query->paginate(20);
        $grouped = $paginated->getCollection()->groupBy(function ($item) {
            return ucfirst(explode('.', $item->name)[0]);
        });

        $paginated->setCollection($grouped);

        return view('livewire.master-data.permission.index', [
            'permissions' => $paginated,
        ]);
    }

    public function generatePermissions()
    {
        $this->validate([
            'moduleName' => 'required|string',
        ]);

        $modelsPath = app_path('Models');
        if (!File::exists($modelsPath)) {
            $this->dispatch('showError', "Direktori Models tidak ditemukan.");
            return;
        }

        $files = File::allFiles($modelsPath);

        $match = collect($files)->first(function ($file) {
            return strtolower(pathinfo($file, PATHINFO_FILENAME)) === strtolower($this->moduleName);
        });

        if (!$match) {
            $this->dispatch('showError', "Model '{$this->moduleName}' tidak ditemukan di app/Models (termasuk subfolder).");
            return;
        }

        // generate permission name dari nama file model
        $modelName = strtolower(pathinfo($match, PATHINFO_FILENAME)); // misal: DataIT → datait

        $crud = ['create', 'read', 'update', 'delete'];

        foreach ($crud as $action) {
            Permission::firstOrCreate(['name' => "{$modelName}.{$action}"]);
        }

        $this->reset('moduleName');
        $this->dispatch('showSuccess', 'Permission CRUD berhasil digenerate.');
    }
    public function delete($id)
    {
        $permission = Permission::findOrFail($id);
        $name = $permission->name;
        $permission->delete();

        $this->dispatch('showSuccess', "Permission '{$name}' berhasil dihapus.");
    }

    public $modelSuggestions = [];

    public function updatedModuleName($value)
    {
        $this->modelSuggestions = $this->getModelSuggestions($value);
    }

    public function getModelSuggestions($input)
    {
        $modelsPath = app_path('Models');
        if (!File::exists($modelsPath)) return [];

        $files = File::allFiles($modelsPath); // cari semua file rekursif

        return collect($files)
            ->map(function ($file) use ($modelsPath) {
                $nameOnly = pathinfo($file, PATHINFO_FILENAME); // contoh: DataIT
                $relativePath = str($file->getPathname())->after($modelsPath . DIRECTORY_SEPARATOR);
                $fullClass = str($relativePath)->replace(['/', '.php'], ['\\', '']);
                return [
                    'name' => $nameOnly,
                    'class' => 'App\\Models\\' . $fullClass, // full class path
                    'base' => strtolower($nameOnly),
                ];
            })
            ->filter(fn($item) => str($item['name'])->lower()->contains(strtolower($input)))
            ->take(5)
            ->values()
            ->toArray();
    }



    public function openAddModal()
    {
        $this->reset('manualName');
        $this->showAddModal = true;
    }

    // Simpan permission manual
    public function storeManualPermission()
    {
        $this->validate([
            'manualName' => 'required|string|unique:permissions,name',
        ]);

        Permission::create(['name' => $this->manualName]);

        $this->showAddModal = false;
        $this->manualName = null;

        $this->dispatch('showSuccess', 'Permission berhasil ditambahkan.');
    }
    // Untuk Ambil Model mana saja yang ada
    public function loadAvailableModels()
    {
        $modelPath = app_path('Models');

        if (!File::exists($modelPath)) return;

        $files = File::files($modelPath);

        $this->availableModels = collect($files)
            ->filter(fn($file) => $file->getExtension() === 'php')
            ->map(fn($file) => str_replace('.php', '', $file->getFilename()))
            ->sort()
            ->values()
            ->all();
    }
}
