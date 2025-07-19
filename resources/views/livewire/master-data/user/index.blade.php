<div class="p-4 space-y-4">
    {{-- Search dan Tambah --}}
    <div class="flex justify-between items-center">
        <div class="relative w-full max-w-md">
            <input type="text" wire:model.live="search" placeholder="Cari nama/email..."
                class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 dark:text-gray-400" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.6 3.6a7.5 7.5 0 0013.05 13.05z" />
            </svg>
        </div>

        <a href="{{ route('roles.index') }}"
            class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">
            Kelola Role
        </a>

        <button wire:click="openModal" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            + Tambah
        </button>
    </div>

    {{-- Table --}}
    <div class="overflow-auto bg-white dark:bg-gray-900 shadow rounded-xl">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 uppercase">
                <tr>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2">Aktif</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr class="border-t border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->roles->pluck('name')->join(', ') ?: '-' }}</td>
                    <td class="px-4 py-2">
                        <span class="inline-block px-2 py-1 text-xs rounded {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 space-x-2">
                        <button wire:click="openModal({{ $user->id }})" class="text-blue-600 dark:text-blue-400">Edit</button>
                        <button wire:click="delete({{ $user->id }})" class="text-red-600 dark:text-red-400"
                            onclick="return confirm('Yakin ingin hapus user ini?')">Hapus</button>
                        <button wire:click="openPermissionModal({{ $user->id }})" class="text-yellow-600 dark:text-yellow-400">
                            Permission
                        </button>
                        <button wire:click="showUserDetail({{ $user->id }})"
                            class="text-gray-600 dark:text-gray-300 hover:text-blue-600">Detail</button>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center text-gray-500">Tidak ada data</td>
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
    </script>
    @endpush
</div>