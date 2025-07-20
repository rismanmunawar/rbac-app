<div class="p-4 space-y-4">
    {{-- Header --}}
    <div class="flex justify-between items-center">
        <div class="relative w-full max-w-md">
            <input type="text" wire:model.live="search" placeholder="Cari role..."
                class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 bg-white text-gray-900 dark:text-grey-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 dark:text-gray-400" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.6 3.6a7.5 7.5 0 0013.05 13.05z" />
            </svg>
        </div>
        @can('roles.create')
        <button wire:click="openModal"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-300 ease-in-out [will-change:background-color]">
            + Tambah
        </button>
        @endcan
    </div>

    {{-- Table --}}
    <div class="overflow-auto bg-white shadow rounded-xl">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="px-4 py-2">Nama Role</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @forelse ($roles as $role)
                <tr wire:key="role-{{ $role->id }}" class="border-t border-gray-200 hover:bg-gray-50 transition">
                    <td class="px-4 py-2">{{ $role->name }}</td>
                    <td class="px-4 py-2 flex flex-wrap gap-1 text-xs" x-data>
                        @can('roles.update')
                        <button wire:click.prevent="openModal({{ $role->id }})"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded 
        bg-blue-900 text-blue-200 
        hover:bg-blue-800 hover:ring-2 hover:ring-blue-400/60 
        dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800 
        transition duration-200 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5.586-9.586a2 2 0 112.828 2.828L11 16l-4 1 1-4 7.414-7.414z" />
                            </svg>
                            Update
                        </button>
                        @endcan

                        @can('roles.delete')
                        <button onclick="confirmDelete({{ $role->id }})"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded 
        bg-red-900 text-red-200 
        hover:bg-red-800 hover:ring-2 hover:ring-red-400/60 
        dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-800 
        transition duration-200 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Delete
                        </button>
                        @endcan


                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="px-4 py-2 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div>{{ $roles->links() }}</div>

    {{-- Modal --}}
    @if ($modalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 w-full max-w-4xl shadow-xl overflow-hidden space-y-6">
            <div class="text-2xl font-semibold text-gray-800 dark:text-white">
                {{ $roleId ? 'Edit Role' : 'Tambah Role' }}
            </div>

            <form wire:submit.prevent="save" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Role</label>
                    <input type="text" wire:model.defer="name"
                        class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('name')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permissions</label>
                    <div
                        class="space-y-6 max-h-[400px] overflow-y-auto border rounded-lg p-4 bg-gray-50 dark:bg-gray-800">
                        @foreach ($permissions as $module => $modulePermissions)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-semibold text-sm text-blue-600 uppercase">
                                    {{ ucfirst($module) }}
                                </span>
                                <label class="text-xs text-gray-600 dark:text-gray-300 space-x-1">
                                    <input type="checkbox" wire:model="selectAll.{{ $module }}"
                                        wire:change="toggleSelectAll('{{ $module }}')"
                                        class="rounded text-blue-600">
                                    <span>Pilih Semua</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                @foreach ($modulePermissions as $permission)
                                <label
                                    class="flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-200">
                                    <input type="checkbox" wire:model.defer="selectedPermissions"
                                        value="{{ $permission }}"
                                        class="rounded text-blue-600 focus:ring-blue-500">
                                    <span>{{ str_replace($module . '.', '', $permission) }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2 border text-gray-700 dark:text-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300 ease-in-out [will-change:background-color]">
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

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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

        Livewire.on('deleted', (message) => {
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