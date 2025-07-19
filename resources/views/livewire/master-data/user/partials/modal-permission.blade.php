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
            <button wire:click="$set('showPermissionModal', false)"
                class="px-4 py-2 border rounded text-gray-700 dark:text-gray-300">Batal</button>
            <button wire:click="savePermissions"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
        </div>
    </div>
</div>
@endif