@props(['label', 'value' => null, 'class' => ''])

<div class="flex items-start gap-2 {{ $class }}">
    <span class="font-semibold w-28 shrink-0">{{ $label }}</span>
    <div class="flex-1">: {!! $value ?? '-' !!}</div>
</div>