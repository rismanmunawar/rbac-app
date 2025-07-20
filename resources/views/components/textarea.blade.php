@props([
'label' => '',
'required' => false,
])

<div class="space-y-1">
    @if($label)
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ $label }}
        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>
    @endif

    <textarea
        {{ $attributes->merge([
            'rows' => 3,
            'class' => 'w-full px-4 py-2 border rounded-md text-sm 
                        dark:bg-gray-800 dark:text-white dark:border-gray-600 
                        focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 
                        transition ease-in-out duration-150'
        ]) }}></textarea>

    @error($attributes->wire('model')->value)
    <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>


<!-- Contoh Pakai -->
<!-- <x-textarea label="Keterangan" wire:model.defer="note" :required="false" /> -->