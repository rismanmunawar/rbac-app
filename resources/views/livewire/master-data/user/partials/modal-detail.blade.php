<x-modal wire:model.defer="showUserDetailModal" class="!max-w-screen-lg">
    <x-slot name="title">Detail User</x-slot>

    @if ($selectedUserDetail)
    <div class="px-6 py-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-5 text-sm text-gray-800 dark:text-gray-200">

            <div class="flex items-start gap-2">
                <span class="font-semibold w-28">NIK</span>
                <span class="flex-1">: {{ $selectedUserDetail->nik ?: '-' }}</span>
            </div>

            <div class="flex items-start gap-2">
                <span class="font-semibold w-28">Alias</span>
                <span class="flex-1">: {{ $selectedUserDetail->alias ?: '-' }}</span>
            </div>

            <div class="flex items-start gap-2">
                <span class="font-semibold w-28">Nama</span>
                <span class="flex-1">: {{ $selectedUserDetail->name }}</span>
            </div>

            <div class="flex items-start gap-2">
                <span class="font-semibold w-28">Designation</span>
                <span class="flex-1">: {{ $selectedUserDetail->designation ?: '-' }}</span>
            </div>

            <div class="flex items-start gap-2">
                <span class="font-semibold w-28">Phone</span>
                <span class="flex-1">: {{ $selectedUserDetail->phone ?: '-' }}</span>
            </div>

            <div class="flex items-start gap-2">
                <span class="font-semibold w-28">Plant</span>
                <span class="flex-1">: {{ $selectedUserDetail->plant ?: '-' }}</span>
            </div>

            <div class="flex items-start gap-2">
                <span class="font-semibold w-28">Role</span>
                <span class="flex-1">: {{ $selectedUserDetail->roles->pluck('name')->join(', ') ?: '-' }}</span>
            </div>

            <div class="flex items-start gap-2">
                <span class="font-semibold w-28">Status</span>
                <span class="flex-1">
                    : <span class="px-2 py-1 text-xs rounded text-white {{ $selectedUserDetail->is_active ? 'bg-green-600' : 'bg-red-600' }}">
                        {{ $selectedUserDetail->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </span>
            </div>

            <div class="flex items-start gap-2 md:col-span-2">
                <span class="font-semibold w-28">Email</span>
                <span class="flex-1">: {{ $selectedUserDetail->email }}</span>
            </div>

            <div class="flex items-start gap-2 md:col-span-2">
                <span class="font-semibold w-28">Modified</span>
                <div class="flex-1">
                    @if ($lastUserLog)
                    <div class="mb-1">: {{ $lastUserLog->created_at->format('d M Y H:i') }}</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">
                        oleh: {{ $lastUserLog->causer->name ?? '-' }}
                    </div>
                    @else
                    <span class="text-gray-500 italic">: Belum ada data perubahan</span>
                    @endif
                </div>
            </div>

        </div>
    </div>
    @endif

    <x-slot name="footer">
        <button wire:click="$set('showUserDetailModal', false)"
            class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">
            Tutup
        </button>
    </x-slot>
</x-modal>