<div class="p-4 space-y-4 max-w-xl mx-auto">
    <h2 class="text-xl font-semibold">{{ $userId ? 'Edit User' : 'Tambah User' }}</h2>

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block mb-1 text-sm font-medium">Nama</label>
            <input type="text" wire:model.defer="name"
                class="w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium">Email</label>
            <input type="email" wire:model.defer="email"
                class="w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium">Password</label>
            <input type="password" wire:model.defer="password"
                class="w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                placeholder="{{ $userId ? 'Biarkan kosong jika tidak ingin mengubah' : '' }}" />
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('users.index') }}"
                class="px-4 py-2 rounded-md border dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                Batal
            </a>
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Simpan
            </button>
        </div>
    </form>
</div>