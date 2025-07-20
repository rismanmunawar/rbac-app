<div class="p-4 space-y-4">
    {{-- Search dan Tambah --}}
    <div class="flex justify-between items-center">
        <div class="relative w-full max-w-md">
            <input type="text" wire:model.live="search" placeholder="Cari nama/email..."
                class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 bg-white  text-gray-900 dark:text-grey-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 dark:text-gray-400" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.6 3.6a7.5 7.5 0 0013.05 13.05z" />
            </svg>
        </div>
        @can('user.create')
        <button wire:click="openModal" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            + Tambah
        </button>
        @endcan

    </div>

    {{-- Table --}}
    <div class="overflow-auto bg-white shadow rounded-xl">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @forelse ($users as $user)
                <tr class="border-t border-gray-200 hover:bg-gray-50 transition">
                    <td class="px-4 py-2 text-text dark:text-text-dark">
                        {{ $user->name }}
                    </td>
                    <td class="px-4 py-2 text-text dark:text-text-dark">
                        {{ $user->email }}
                    </td>
                    <td class="px-4 py-2 text-text dark:text-text-dark">
                        {{ $user->roles->pluck('name')->join(', ') ?: '-' }}
                    </td>
                    <td class="px-4 py-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:click="toggleActive({{ $user->id }})"
                                {{ $user->is_active ? 'checked' : '' }} class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-300 peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer-checked:bg-green-500 transition-colors duration-300 ease-in-out">
                            </div>
                            <div
                                class="absolute left-0.5 top-0.5 w-5 h-5 bg-white border border-gray-300 rounded-full transition-transform duration-300 ease-in-out peer-checked:translate-x-full">
                            </div>
                        </label>
                    </td>
                    <td class="px-4 py-2 flex flex-wrap gap-1 text-xs">

                        @can('user.update')
                        <button wire:click="openModal({{ $user->id }})" title="Edit"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded 
            bg-blue-900 text-blue-200 
            hover:bg-blue-800 hover:ring-2 hover:ring-blue-400/60 
            transition duration-200 ease-in-out [will-change:background-color]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5.586-9.586a2 2 0 112.828 2.828L11 16l-4 1 1-4 7.414-7.414z" />
                            </svg>
                            Update
                        </button>
                        @endcan

                        @can('user.delete')
                        <button onclick="confirmDelete({{ $user->id }})" title="Delete"
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

                        @can('user.update')
                        <button wire:click="openPermissionModal({{ $user->id }})" title="Manage Permission"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded 
            bg-yellow-900 text-yellow-200 
            hover:bg-yellow-800 hover:ring-2 hover:ring-yellow-400/60 
            transition duration-200 ease-in-out [will-change:background-color]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4v1m0 14v1m8-8h1M4 12H3m15.364-6.364l.707.707M6.343 17.657l-.707.707M17.657 17.657l.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                            </svg>
                            Permission
                        </button>
                        @endcan

                        @can('user.read')
                        <button wire:click="showUserDetail({{ $user->id }})" title="View Details"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded 
            bg-gray-800 text-gray-100 
            hover:bg-gray-700 hover:ring-2 hover:ring-gray-400/60 
            transition duration-200 ease-in-out [will-change:background-color]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            View
                        </button>
                        @endcan

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                        Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    <div>{{ $users->links() }}</div>
    @include('livewire.master-data.user.partials.modal-detail')
    @include('livewire.master-data.user.partials.modal-form')
    @include('livewire.master-data.user.partials.modal-permission')
    @include('livewire.master-data.user.partials.modal-log')

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