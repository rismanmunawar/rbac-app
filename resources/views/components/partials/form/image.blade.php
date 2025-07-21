@props([
'label' => null,
'old' => null,
])

@php
$modelKey = $attributes->wire('model')->value();
$value = $modelKey && isset($this->{$modelKey}) ? $this->{$modelKey} : null;
@endphp

<div class="md:col-span-2 flex items-center gap-4">
    @if ($label)
    <label class="font-medium w-24 shrink-0">{{ $label }}</label>
    @endif

    <div class="flex flex-col gap-1 w-full">
        <input
            type="file"
            @if ($modelKey) wire:model="{{ $modelKey }}" @endif
            class="block text-xs w-full border border-gray-300 rounded py-2 h-[42px] text-sm"
            {{ $attributes->except('wire:model') }} />

        @error($modelKey)
        <p class="text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    @if ($value instanceof \Livewire\TemporaryUploadedFile)
    <img src="{{ $value->temporaryUrl() }}" class="w-12 h-12 object-cover rounded border" />
    @elseif ($old)
    <img src="{{ Storage::url($old) }}" class="w-12 h-12 object-cover rounded border" />
    @endif
</div>