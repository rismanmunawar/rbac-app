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
        $this->validate();

        $base = strtolower($this->moduleName);
        $modelName = ucfirst($base); // misal: "user" jadi "User"
        $modelPath = app_path("Models/{$modelName}.php");

        // ❌ Jika model tidak ditemukan, tolak
        if (!File::exists($modelPath)) {
            $this->dispatch('showError', "Model '{$modelName}' tidak ditemukan di app/Models.");
            return;
        }

        $crud = ['create', 'read', 'update', 'delete'];

        foreach ($crud as $action) {
            Permission::firstOrCreate(['name' => "{$base}.{$action}"]);
        }

        $this->reset(['moduleName']);
        $this->dispatch('showSuccess', 'Permission CRUD berhasil digenerate.');
    }


    public function delete($id)
    {
        $permission = Permission::findOrFail($id);
        $name = $permission->name;
        $permission->delete();

        $this->dispatch('showSuccess', "Permission '{$name}' berhasil dihapus.");
    }



    public function deleteSelected()
    {
        Permission::whereIn('id', $this->selectedIds)->delete();
        $this->reset(['selectedIds', 'selectAll']);
        $this->dispatch('showSuccess', 'Permission terpilih berhasil dihapus.');
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

        $files = File::files($modelsPath);

        return collect($files)
            ->map(fn($file) => pathinfo($file, PATHINFO_FILENAME))
            ->filter(fn($name) => str($name)->lower()->contains(strtolower($input)))
            ->values()
            ->take(5) // batasi 5 saran teratas
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