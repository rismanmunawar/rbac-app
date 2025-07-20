@props([
'label' => '',
'type' => 'text',
'required' => false,
'placeholder' => '',
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

    <input
        type="{{ $type }}"
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2 h-[42px] border rounded-md text-sm 
                        dark:bg-gray-800 dark:text-white dark:border-gray-600 
                        focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 
                        transition ease-in-out duration-150',
            'placeholder' => $placeholder,
        ]) }} />
    @error($attributes->wire('model')->value)
    <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>