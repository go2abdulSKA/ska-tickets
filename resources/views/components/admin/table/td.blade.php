{{-- resources/views/components/table/td.blade.php --}}
@props([
    'align' => 'left',
    'valign' => 'middle',
    'width' => null,
    'nowrap' => false,
])

@php
    $classes = '';

    if ($align === 'center') $classes .= ' text-center';
    if ($align === 'right') $classes .= ' text-end';

    if ($valign === 'top') $classes .= ' align-top';
    if ($valign === 'middle') $classes .= ' align-middle';
    if ($valign === 'bottom') $classes .= ' align-bottom';

    if ($nowrap) $classes .= ' text-nowrap';

    $classes .= ' ' . ($attributes->get('class') ?? '');

    $style = $width ? "width: {$width};" : '';
@endphp

<td {{ $attributes->merge(['class' => trim($classes)]) }} @if($style) style="{{ $style }}" @endif>
    {{ $slot }}
</td>

{{--
Usage Examples:

1. Basic Cell:
<x-table.td>John Doe</x-table.td>

2. Center Aligned:
<x-table.td align="center">Active</x-table.td>

3. Right Aligned (for numbers):
<x-table.td align="right">$1,234.56</x-table.td>

4. Top Aligned:
<x-table.td valign="top">
    Multi-line content
</x-table.td>

5. No Wrap with Fixed Width:
<x-table.td :nowrap="true" width="150px">
    Long text that won't wrap
</x-table.td>

6. Actions Column:
<x-table.td align="center" :nowrap="true">
    <button class="btn btn-sm btn-primary">
        <i class="mdi mdi-pencil"></i>
    </button>
    <button class="btn btn-sm btn-danger">
        <i class="mdi mdi-delete"></i>
    </button>
</x-table.td>

7. Complete Example:
<x-table>
    <thead>
        <tr>
            <x-table.th width="50px">#</x-table.th>
            <x-table.th>Name</x-table.th>
            <x-table.th align="center" width="100px">Status</x-table.th>
            <x-table.th align="center" width="150px">Actions</x-table.th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <x-table.td align="center">1</x-table.td>
            <x-table.td>John Doe</x-table.td>
            <x-table.td align="center">
                <span class="badge bg-success">Active</span>
            </x-table.td>
            <x-table.td align="center" :nowrap="true">
                <button class="btn btn-sm btn-primary">Edit</button>
                <button class="btn btn-sm btn-danger">Delete</button>
            </x-table.td>
        </tr>
    </tbody>
</x-table>
--}}
