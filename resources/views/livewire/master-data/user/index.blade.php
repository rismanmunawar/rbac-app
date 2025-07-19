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
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2">Aktif</th>
                    <th class="px-4 py-2">Modified</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr class="border-t border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">{{ $user->roles->pluck('name')->join(', ') ?: '-' }}</td>
                    <td class="px-4 py-2">
                        <span class="inline-block px-2 py-1 text-xs rounded {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        @php
                        $lastLog = \App\Models\ActivityLog::where('subject_type', \App\Models\User::class)
                        ->where('subject_id', $user->id)
                        ->where('action', 'like', 'update_user%')
                        ->latest()
                        ->first();
                        @endphp

                        @if ($lastLog)
                        <div class="text-sm text-gray-800 dark:text-gray-100">
                            <span>Oleh: {{ $lastLog->causer->name ?? '-' }}</span><br>
                            <button wire:click="showLogDetail({{ $user->id }})" class="text-blue-600 dark:text-blue-400 underline text-xs">
                                Detail
                            </button>
                        </div>
                        @else
                        <span class="text-sm text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 space-x-2">
                        <button wire:click="openModal({{ $user->id }})" class="text-blue-600 dark:text-blue-400">Edit</button>
                        <button wire:click="delete({{ $user->id }})" class="text-red-600 dark:text-red-400"
                            onclick="return confirm('Yakin ingin hapus user ini?')">Hapus</button>
                        <button wire:click="openPermissionModal({{ $user->id }})" class="text-yellow-600 dark:text-yellow-400">
                            Permission
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $users->links() }}</div>

    {{-- Modal Tambah/Edit --}}
    @if($modalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 w-full max-w-lg space-y-6 shadow-xl">
            <div class="text-lg font-semibold text-gray-800 dark:text-white">
                {{ $userId ? 'Edit User' : 'Tambah User' }}
            </div>

            <form wire:submit.prevent="save" class="space-y-4">
                <x-input label="Nama" wire:model.defer="name" />
                <x-input label="Email" type="email" wire:model.defer="email" />
                <x-input label="Password" type="password" wire:model.defer="password" placeholder="{{ $userId ? 'Kosongkan jika tidak diubah' : '' }}" />

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                    <select wire:model.defer="role" class="w-full mt-1 px-3 py-2 border rounded-md dark:bg-gray-800 dark:text-white">
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $r)
                        <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" wire:model.defer="is_active" id="is_active" class="rounded">
                    <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Aktif</label>
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

    {{-- Modal Permissions --}}
    @if($showPermissionModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 w-full max-w-2xl">
            <div class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Kelola Permission User</div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-96 overflow-y-auto">
                @foreach($allPermissions as $perm)
                <label class="flex items-center space-x-2 text-sm">
                    <input type="checkbox" wire:model="userPermissions" value="{{ $perm }}" class="rounded">
                    <span>{{ $perm }}</span>
                </label>
                @endforeach
            </div>
            <div class="mt-4 flex justify-end space-x-2">
                <button wire:click="$set('showPermissionModal', false)" class="px-4 py-2 border rounded text-gray-700 dark:text-gray-300">Batal</button>
                <button wire:click="savePermissions" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </div>
    </div>
    @endif
    {{-- Modal Log Aktivitas --}}
    @if($logModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 w-full max-w-2xl">
            <div class="text-lg font-bold mb-4 text-gray-800 dark:text-white">Detail Log Terakhir</div>

            @if($logDetails)
            <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                <p><strong>Waktu:</strong> {{ $logDetails['created_at'] }}</p>
                <p><strong>Oleh:</strong> {{ $logDetails['causer'] }}</p>
                <p><strong>Aksi:</strong> {{ $logDetails['action'] }}</p>
                <p><strong>Perubahan:</strong></p>
                <ul class="list-disc ml-5">
                    @foreach($logDetails['changes'] as $field => $change)
                    <li>{{ $field }}: "{{ $change['before'] }}" → "{{ $change['after'] }}"</li>
                    @endforeach
                </ul>
            </div>
            @else
            <p class="text-gray-500">Tidak ada data log.</p>
            @endif

            <div class="mt-4 text-right">
                <button wire:click="$set('logModal', false)" class="px-4 py-2 border rounded text-gray-700 dark:text-gray-300">Tutup</button>
            </div>
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
    </script>
    @endpush
</div>