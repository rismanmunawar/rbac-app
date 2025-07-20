<div class="p-4 space-y-4">
    {{-- Header --}}
    <div class="flex flex-wrap justify-between items-center gap-2">
        <div class="relative w-full max-w-md">
            <input type="text" wire:model.live="search" placeholder="Cari permission..."
                class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.6 3.6a7.5 7.5 0 0013.05 13.05z" />
            </svg>
        </div>

        <div class="flex items-center gap-2">
            <input type="text" wire:model.defer="moduleName" placeholder="Nama Modul (contoh: Users)"
                class="px-3 py-2 rounded-md border border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500">
            <button wire:click="generatePermissions"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Generate CRUD
            </button>
            <button wire:click="$set('showAddModal', true)"
                class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                + Add Permission Manual
            </button>
        </div>
    </div>

    {{-- Daftar Model Asli di App\Models --}}
    @if (count($availableModels))
    <div class="bg-white p-4 rounded shadow mt-6">
        <h3 class="font-semibold text-gray-700 mb-2">Model yang Terdeteksi di <code>App\Models</code>:</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            @foreach ($availableModels as $model)
            <div class="bg-green-100 px-3 py-2 rounded text-sm text-gray-800">
                <span class="block font-semibold">{{ $model }}</span>
                <span class="block text-xs text-gray-600 italic">App\Models\{{ $model }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    {{-- Tabel Permission --}}
    <div class="overflow-auto bg-white shadow rounded-xl">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="px-4 py-2">Nama Permission</th>
                    <th class="px-4 py-2">Model</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($permissions as $module => $group)
                <tr class="bg-gray-200 text-gray-800 font-bold">
                    <td colspan="4" class="px-4 py-2 uppercase">
                        Modul: {{ $module }}
                    </td>
                </tr>
                @foreach ($group as $permission)
                <tr class="border-t border-gray-200">
                    <td class="px-4 py-2 text-xs text-gray-600">{{ $permission->name }}</td>
                    <td class="px-4 py-2 text-xs text-gray-600">
                        {{ 'App\\Models\\' . ucfirst(explode('.', $permission->name)[0]) }}
                    </td>
                    <td class="px-4 py-2">
                        @can('permissions.delete')
                        <button onclick="confirmDelete({{ $permission->id }})" title="Delete"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded 
            bg-red-900 text-red-200 
            hover:bg-red-800 hover:ring-2 hover:ring-red-400/60 
            transition duration-200 ease-in-out [will-change:background-color]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Delete
                        </button>
                        @endcan
                    </td>
                </tr>
                @endforeach
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div>{{ $permissions->links() }}</div>

    {{-- Modal Manual Permission --}}
    @if ($showAddModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 w-full max-w-lg shadow-xl overflow-hidden space-y-6">
            <div class="text-xl font-semibold text-gray-800 dark:text-white">
                Tambah Permission Manual
            </div>

            <form wire:submit.prevent="storeManualPermission" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama
                        Permission</label>
                    <input type="text" wire:model.defer="manualName"
                        placeholder="Contoh: posts.toggle / content.hide"
                        class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('manualName')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="$set('showAddModal', false)"
                        class="px-4 py-2 border text-gray-700 dark:text-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- SweetAlert --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Tindakan ini tidak bisa dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteConfirmed', {
                        id: id
                    });
                }
            });
        }

        Livewire.on('showSuccess', message => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: message,
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true,
            });
        })
        Livewire.on('showError', message => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: message,
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true,
            });
        });
    </script>
    @endpush
</div>