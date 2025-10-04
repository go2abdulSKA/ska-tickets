{{-- resources/views/components/table/th.blade.php --}}
@props([
    'sortable' => false,
    'active' => false,
    'direction' => 'asc',
    'width' => null,
    'align' => 'left',
])

@php
    $classes = '';

    if ($align === 'center') $classes .= ' text-center';
    if ($align === 'right') $classes .= ' text-end';

    if ($sortable) {
        $classes .= ' sortable';
    }

    if ($active) {
        $classes .= ' sorting-active';
    }

    $classes .= ' ' . ($attributes->get('class') ?? '');

    $style = $width ? "width: {$width};" : '';
@endphp

<th {{ $attributes->merge(['class' => trim($classes)]) }} @if($style) style="{{ $style }}" @endif>
    <div class="d-flex align-items-center {{ $align === 'center' ? 'justify-content-center' : ($align === 'right' ? 'justify-content-end' : '') }}">
        <span>{{ $slot }}</span>

        @if($sortable)
            <span class="ms-1 sort-icon">
                @if($active)
                    @if($direction === 'asc')
                        <i class="mdi mdi-arrow-up"></i>
                    @else
                        <i class="mdi mdi-arrow-down"></i>
                    @endif
                @else
                    <i class="mdi mdi-unfold-more-horizontal text-muted"></i>
                @endif
            </span>
        @endif
    </div>
</th>

@pushOnce('styles')
<style>
    th.sortable {
        cursor: pointer;
        user-select: none;
        position: relative;
    }

    th.sortable:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    th.sorting-active {
        background-color: rgba(var(--bs-primary-rgb), 0.05);
    }

    th.sortable .sort-icon {
        opacity: 0.6;
        transition: opacity 0.2s;
    }

    th.sortable:hover .sort-icon {
        opacity: 1;
    }

    th.sorting-active .sort-icon {
        opacity: 1;
    }
</style>
@endPushOnce

{{--
Usage Examples:

1. Regular Column:
<x-table.th>Name</x-table.th>

2. Sortable Column (with Livewire):
<x-table.th
    sortable
    wire:click="sortBy('name')"
    :active="$sortField === 'name'"
    :direction="$sortDirection"
>
    Name
</x-table.th>

3. Center Aligned with Width:
<x-table.th align="center" width="100px">Status</x-table.th>

4. Right Aligned:
<x-table.th align="right" width="120px">Amount</x-table.th>
--}}
