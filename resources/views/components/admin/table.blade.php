{{-- resources/views/components/table.blade.php --}}
@props([
    'hover' => true,
    'striped' => true,
    'bordered' => false,
    'borderless' => false,
    'small' => false,
    'responsive' => true,
    'dark' => false,
])

@php
    $classes = 'table';
    if ($hover) $classes .= ' table-hover';
    if ($striped) $classes .= ' table-striped';
    if ($bordered) $classes .= ' table-bordered';
    if ($borderless) $classes .= ' table-borderless';
    if ($small) $classes .= ' table-sm';
    if ($dark) $classes .= ' table-dark';

    $classes .= ' ' . ($attributes->get('class') ?? '');
@endphp

@if($responsive)
    <div class="table-responsive">
        <table {{ $attributes->merge(['class' => $classes]) }}>
            {{ $slot }}
        </table>
    </div>
@else
    <table {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </table>
@endif

{{--
Usage Examples:

1. Basic Table:
<x-table>
    <thead>
        <tr>
            <x-table.th>Name</x-table.th>
            <x-table.th>Email</x-table.th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <x-table.td>John Doe</x-table.td>
            <x-table.td>john@example.com</x-table.td>
        </tr>
    </tbody>
</x-table>

2. Bordered Small Table:
<x-table :bordered="true" :small="true">
    ...
</x-table>

3. Non-responsive Table:
<x-table :responsive="false">
    ...
</x-table>
--}}
