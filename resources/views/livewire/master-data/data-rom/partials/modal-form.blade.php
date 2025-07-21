@if($showModal)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-900 rounded-xl p-6 w-full max-w-2xl space-y-6 shadow-xl">
        <div class="text-lg font-semibold text-gray-800 dark:text-white">
            {{ $isEdit ? 'Edit Data Rom' : 'Tambah Data Rom' }}
        </div>
        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <x-input label="NIK" wire:model.defer="nik" type="number" />
            <x-input label="Alias" wire:model.defer="alias" />
            <x-input label="Nama" wire:model.defer="name" name="name" required="true" />
            <x-input label="Phone" wire:model.defer="phone" />
            <x-input label="Email" type="email" wire:model.defer="email" class="md:col-span-2" required="true" />
            <div class="md:col-span-2 flex items-center gap-2">
                <x-switch label="Status Aktif" wire:model.defer="status" />
            </div>
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