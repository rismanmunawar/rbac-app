@if($modalOpen)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-900 rounded-xl p-6 w-full max-w-3xl space-y-6 shadow-xl">
        <div class="text-lg font-semibold text-gray-800 dark:text-white">
            {{ $userId ? 'Edit User' : 'Tambah User' }}
        </div>

        <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <x-input label="NIK" wire:model.defer="nik" />
            <x-input label="Alias" wire:model.defer="alias" />
            <x-input label="Nama" wire:model.defer="name" />
            <x-input label="Designation" wire:model.defer="designation" />
            <x-input label="Phone" wire:model.defer="phone" />
            <x-input label="Plant" wire:model.defer="plant" />
            <x-input label="Email" type="email" wire:model.defer="email" class="md:col-span-2" />
            <x-input label="Password" type="password" wire:model.defer="password" autocomplete="password"
                placeholder="{{ $userId ? 'Kosongkan jika tidak diubah' : '' }}"
                class="md:col-span-2" />

            {{-- Role --}}
            <x-select label="Role" wire:model.defer="role" :required="true">
                <option value="">-- Pilih Role --</option>
                @foreach ($roles as $r)
                <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                @endforeach
            </x-select>


            {{-- Status Aktif --}}
            <x-switch label="Status Aktif" wire:model="is_active" model="is_active" />

            {{-- Tombol --}}
            <div class="flex justify-end gap-2 pt-4 md:col-span-2">
                <button wire:click="closeModal" type="button"
                    class="px-4 py-2 border rounded text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
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