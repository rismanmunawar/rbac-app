@props([
'label' => '',
'model' => '',
])

<div class="flex items-center justify-between py-2">
    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ $label }}
    </label>
    <button type="button"
        wire:click="$set('{{ $model }}', !@js($attributes->wire('model')->value ?? false))"
        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 focus:outline-none
            {{ $attributes->wire('model')->value ? 'bg-green-500' : 'bg-gray-300' }}">
        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300
            {{ $attributes->wire('model')->value ? 'translate-x-6' : 'translate-x-1' }}"></span>
    </button>
</div>