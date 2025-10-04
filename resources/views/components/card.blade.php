@props(['title' => null, 'actions' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-gray-200']) }}>
    @if($title || $actions)
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        @if($title)
        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
        @endif
        
        @if($actions)
        <div class="flex items-center space-x-2">
            {{ $actions }}
        </div>
        @endif
    </div>
    @endif

    <div class="px-6 py-4">
        {{ $slot }}
    </div>
</div>
