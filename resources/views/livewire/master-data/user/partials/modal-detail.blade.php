<x-modal wire:model.defer="showUserDetailModal">
    <x-slot name="title">Detail User</x-slot>

    @if ($selectedUserDetail)
    <div class="space-y-3 text-sm text-gray-800 dark:text-gray-200">
        <div class="flex items-center gap-2">
            <span class="font-semibold w-32">Nama</span>
            <span class="flex-1">: {{ $selectedUserDetail->name }}</span>
        </div>

        <div class="flex items-center gap-2">
            <span class="font-semibold w-32">Email</span>
            <span class="flex-1">: {{ $selectedUserDetail->email }}</span>
        </div>

        <div class="flex items-center gap-2">
            <span class="font-semibold w-32">Role</span>
            <span class="flex-1">: {{ $selectedUserDetail->roles->pluck('name')->join(', ') ?: '-' }}</span>
        </div>

        <div class="flex items-center gap-2">
            <span class="font-semibold w-32">Status</span>
            <span class="flex-1">
                : <span class="px-2 py-1 text-xs rounded text-white {{ $selectedUserDetail->is_active ? 'bg-green-600' : 'bg-red-600' }}">
                    {{ $selectedUserDetail->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </span>
        </div>

        @if ($lastUserLog)
        <div class="flex items-start gap-2">
            <span class="font-semibold w-32">Modified</span>
            <div class="flex-1">
                <div class="mb-1">: {{ $lastUserLog->created_at->format('d M Y H:i') }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">
                    oleh: {{ $lastUserLog->causer->name ?? '-' }}
                </div>
            </div>
        </div>

        {{--
                    Jika suatu saat ingin menampilkan detail perubahan log:
                    @if (!empty($lastUserLog->properties))
                        <div class="flex items-start gap-2">
                            <span class="font-semibold w-32">Perubahan</span>
                            <pre class="flex-1 bg-gray-100 dark:bg-gray-800 p-2 rounded text-xs overflow-auto text-gray-800 dark:text-gray-300">
{{ json_encode($lastUserLog->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
        </pre>
    </div>
    @endif
    --}}
    @else
    <div class="flex items-center gap-2">
        <span class="font-semibold w-32">Modified</span>
        <span class="flex-1 text-gray-500 italic">: Belum ada data perubahan</span>
    </div>
    @endif
    </div>
    @endif

    <x-slot name="footer">
        <button wire:click="$set('showUserDetailModal', false)"
            class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">
            Tutup
        </button>
    </x-slot>
</x-modal>