@props(['title' => null])

@php
$role = auth()->user()?->getRoleNames()->first();
@endphp

{{-- Ini adalah cara yang benar untuk mengomentari kode Blade --}}
{{-- @if ($role === 'Admin') --}}

@if (in_array($role, ['Admin', 'Super Admin']))
<x-layouts.app.sidebar :title="$title">
    {{ $slot }}
</x-layouts.app.sidebar>
@else
<x-layouts.app.header :title="$title">
    {{ $slot }}
</x-layouts.app.header>
@endif