<div class="p-4 space-y-4">

    {{-- Search dan Tambah --}}
    <div class="flex justify-between items-center">
        <div class="relative w-full max-w-md">
            <input type="text" wire:model.live="search" placeholder="Cari nama/email..."
                class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 bg-white text-gray-900 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 dark:text-gray-400"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.6 3.6a7.5 7.5 0 0013.05 13.05z" />
            </svg>
        </div>

        @can('datanom.create')
        <button wire:click="create" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            + Tambah
        </button>
        @endcan
    </div>

    {{-- Table --}}
    <div class="overflow-auto bg-white dark:bg-gray-900 shadow rounded-xl">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 uppercase">
                <tr>
                    <th class="px-4 py-2">NIK</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Alias</th>
                    <th class="px-4 py-2">Telefon</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800 dark:text-gray-200">
                @forelse ($records as $row)
                <tr wire:key="data-nom-{{ $row->id }}"
                    class="border-t border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <td class="px-4 py-2">{{ $row->nik }}</td>
                    <td class="px-4 py-2">{{ $row->name }}</td>
                    <td class="px-4 py-2">{{ $row->alias }}</td>
                    <td class="px-4 py-2">{{ $row->phone }}</td>
                    <td class="px-4 py-2">{{ $row->email }}</td>
                    <td class="px-4 py-2">
                        <span
                            class="inline-flex px-2 py-1 rounded font-medium text-xs {{ $row->status ? 'bg-green-100 text-green-700 dark:bg-green-900/30' : 'bg-gray-300 text-gray-700 dark:bg-gray-900/40 dark:text-gray-400' }}">
                            {{ $row->status ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-2 flex flex-wrap gap-1 text-xs">
                        @can('datanom.update')
                        <button wire:click="edit({{ $row->id }})" title="Edit"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded bg-blue-900 text-blue-200 hover:bg-blue-800 hover:ring-2 hover:ring-blue-400/60 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5.586-9.586a2 2 0 112.828 2.828L11 16l-4 1 1-4 7.414-7.414z" />
                            </svg>
                            Ubah
                        </button>
                        @endcan
                        @can('datanom.delete')
                        <button onclick="confirmDelete({{ $row->id }})" title="Delete"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded bg-red-900 text-red-200 hover:bg-red-800 hover:ring-2 hover:ring-red-400/60 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Hapus
                        </button>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $records->links() }}</div>

    @if($showModal)
    @include('livewire.master-data.data-nom.partials.modal-form')
    @endif

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
                    Livewire.dispatch('deleteConfirmed', id);
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