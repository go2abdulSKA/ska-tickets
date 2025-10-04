@props(['disabled' => false, 'error' => false])

@php
$classes = 'block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm';
if ($error) {
    $classes = 'block w-full rounded-lg border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 sm:text-sm';
}
if ($disabled) {
    $classes .= ' bg-gray-100 cursor-not-allowed';
}
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>
{{-- resources/views/components/input.blade.php --}}
