{{-- resources/views/components/modal.blade.php --}}
@props([
    'id' => 'modal',
    'size' => 'lg', // sm, md, lg, xl, fullscreen
    'title' => 'Modal Title',
    'footer' => true,
    'centered' => false,
    'scrollable' => false,
    'backdrop' => 'true', // true, false, static
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true"
     data-bs-backdrop="{{ $backdrop }}" data-bs-keyboard="true">
    <div class="modal-dialog modal-{{ $size }} {{ $centered ? 'modal-dialog-centered' : '' }} {{ $scrollable ? 'modal-dialog-scrollable' : '' }}">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body">
                {{ $slot }}
            </div>

            {{-- Modal Footer (optional) --}}
            @if($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

{{--
Usage Examples:

1. Basic Modal:
<x-modal id="myModal" title="Add New Item">
    <p>Modal content here</p>
    <x-slot:footer>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save</button>
    </x-slot:footer>
</x-modal>

2. Small Centered Modal without footer:
<x-modal id="confirmModal" size="sm" title="Confirm Action" :footer="false" :centered="true">
    <p>Are you sure?</p>
</x-modal>

3. Full Screen Scrollable Modal:
<x-modal id="detailsModal" size="fullscreen" title="Full Details" :scrollable="true">
    <p>Long content here...</p>
</x-modal>

4. Livewire Integration:
In your Livewire component:
- Open modal: $this->dispatch('openModal', modalId: 'userModal');
- Close modal: $this->dispatch('closeModal', modalId: 'userModal');

Add this to your layout or page:
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('openModal', (data) => {
            const modal = new bootstrap.Modal(document.getElementById(data.modalId));
            modal.show();
        });

        Livewire.on('closeModal', (data) => {
            const modalEl = document.getElementById(data.modalId);
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        });
    });
</script>
--}}
