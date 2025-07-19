<div class="p-4 space-y-4">
    {{-- Header: Search dan Tombol Tambah --}}
    <div class="flex justify-between items-center">
        <div class="relative w-full max-w-md">
            <input type="text" wire:model.live="search" placeholder="Cari role..."
                class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 dark:text-gray-400" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.6 3.6a7.5 7.5 0 0013.05 13.05z" />
            </svg>
        </div>

        <button wire:click="openModal" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            + Tambah
        </button>
    </div>

    {{-- Tabel --}}
    <div class="overflow-auto bg-white dark:bg-gray-900 shadow rounded-xl">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 uppercase">
                <tr>
                    <th class="px-4 py-2">Nama Role</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                <tr class="border-t border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2">{{ $role->name }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <button wire:click="openModal({{ $role->id }})" class="text-blue-600 dark:text-blue-400">Edit</button>
                        <button wire:click="delete({{ $role->id }})" class="text-red-600 dark:text-red-400"
                            onclick="return confirm('Yakin ingin hapus role ini?')">Hapus</button>
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
    @if($modalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 w-full max-w-md space-y-6 shadow-xl transform scale-100 transition duration-300">
            <div class="flex items-center gap-2 text-xl font-semibold text-gray-800 dark:text-white">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 00-8 0v4a4 4 0 008 0V7z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 17v1m0 4h.01M12 21c-4.418 0-8-1.79-8-4V9a8 8 0 0116 0v8c0 2.21-3.582 4-8 4z" />
                </svg>
                {{ $roleId ? 'Edit Role' : 'Tambah Role' }}
            </div>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Role</label>
                    <input type="text" wire:model.defer="name"
                        class="mt-1 w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button wire:click="closeModal" type="button"
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 border rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
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