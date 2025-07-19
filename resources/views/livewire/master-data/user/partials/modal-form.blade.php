@if($modalOpen)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-900 rounded-xl p-6 w-full max-w-lg space-y-6 shadow-xl">
        <div class="text-lg font-semibold text-gray-800 dark:text-white">
            {{ $userId ? 'Edit User' : 'Tambah User' }}
        </div>

        <form wire:submit.prevent="save" class="space-y-4">
            <x-input label="Nama" wire:model.defer="name" />
            <x-input label="Email" type="email" wire:model.defer="email" />
            <x-input label="Password" type="password" wire:model.defer="password" autocomplete="password"
                placeholder="{{ $userId ? 'Kosongkan jika tidak diubah' : '' }}" />

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

            <div class="flex items-center justify-between">
                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Aktif</label>

                <button type="button" wire:click="$set('is_active', {{ $is_active ? 'false' : 'true' }})"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 focus:outline-none
            {{ $is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300
            {{ $is_active ? 'translate-x-6' : 'translate-x-1' }}">
                    </span>
                </button>
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