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
        </div>
    </div>

    {{-- Daftar Modul --}}
    @if($permissions->count())
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold text-gray-700 mb-2">Modul Tersedia:</h3>
        <div class="flex flex-wrap gap-2">
            @foreach ($permissions->getCollection()->keys() as $module)
            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                {{ $module }}
            </span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Bulk Action --}}
    @if(count($selectedIds) > 0)
    <div class="flex justify-between items-center px-4 py-2 bg-red-100 rounded">
        <span class="text-sm font-medium text-red-800">
            {{ count($selectedIds) }} permission dipilih
        </span>
        <button wire:click="deleteSelected"
            class="flex items-center gap-1 text-sm px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
            Hapus Terpilih
        </button>
    </div>
    @endif

    {{-- Tabel Permission --}}
    <div class="overflow-auto bg-white shadow rounded-xl">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="px-4 py-2 w-10">
                        <input type="checkbox" wire:model="selectAll">
                    </th>
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
                    <td class="px-4 py-2">
                        <input type="checkbox" wire:model="selectedIds" value="{{ $permission->id }}">
                    </td>
                    <td class="px-4 py-2 text-xs text-gray-600">{{ $permission->name }}</td>
                    <td class="px-4 py-2 text-xs text-gray-600">
                        {{ 'App\\Models\\' . ucfirst(explode('.', $permission->name)[0]) }}
                    </td>
                    <td class="px-4 py-2">
                        <button onclick="confirmDelete({{ $permission->id }})"
                            class="text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
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
        });
    </script>
    @endpush

</div>