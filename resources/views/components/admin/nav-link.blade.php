@props(['active' => false])

@php
$classes = $active
    ? 'flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-gray-800 text-white'
    : 'flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white transition-colors duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

