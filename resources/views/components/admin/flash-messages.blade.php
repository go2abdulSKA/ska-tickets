@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-transition class="mb-4">
    <x-alert type="success" dismissible>
        <div class="font-medium">Success!</div>
        <div class="mt-1 text-sm">{{ session('success') }}</div>
    </x-alert>
</div>
@endif

@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-transition class="mb-4">
    <x-alert type="error" dismissible>
        <div class="font-medium">Error!</div>
        <div class="mt-1 text-sm">{{ session('error') }}</div>
    </x-alert>
</div>
@endif

@if($errors->any())
<div x-data="{ show: true }" x-show="show" x-transition class="mb-4">
    <x-alert type="error" dismissible>
        <div class="font-medium">Please fix the following errors:</div>
        <ul class="mt-2 space-y-1 text-sm list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
</div>
@endif
{{-- resources/views/components/flash-messages.blade.php --}}
