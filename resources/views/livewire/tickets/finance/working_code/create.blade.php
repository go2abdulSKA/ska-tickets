{{-- resources/views/livewire/tickets/finance/create.blade.php --}}

{{-- <div x-data="financeTicketForm()" x-init="init()"> --}}

<div>
    {{-- Page Header --}}
    <div class="mb-4 page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="m-0 fs-xl fw-bold">
                @if ($editMode)
                    Edit Finance Ticket
                @elseif($isDuplicate)
                    Duplicate Finance Ticket
                @else
                    Create Finance Ticket
                @endif
            </h4>
            <p class="mb-0 text-muted">
                @if ($previewTicketNumber)
                    Next Ticket Number: <span class="badge badge-soft-primary">{{ $previewTicketNumber }}</span>
                @endif
                @if ($lastSaved)
                    <span class="ms-2 text-muted small">
                        <i class="mdi mdi-content-save-outline"></i> Last saved: {{ $lastSaved }}
                    </span>
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('tickets.finance.index') }}" class="btn btn-light">
                <i class="mdi mdi-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="mb-3 card">
        <div class="p-3 card-body">
            <div class="d-flex align-items-center">
                {{-- Step Indicators --}}
                <div class="flex-grow-1">
                    <div class="mb-2 d-flex justify-content-between">
                        @for ($i = 1; $i <= $totalSteps; $i++)
                            <div class="text-center" style="flex: 1;">
                                <div class="step-indicator {{ $currentStep >= $i ? 'active' : '' }}"
                                    wire:click="goToStep({{ $i }})" style="cursor: pointer;">
                                    <div
                                        class="step-circle {{ $currentStep >= $i ? 'bg-primary text-white' : 'bg-light text-muted' }}">
                                        @if ($currentStep > $i)
                                            <i class="mdi mdi-check"></i>
                                        @else
                                            {{ $i }}
                                        @endif
                                    </div>
                                    <div
                                        class="step-label small mt-1 {{ $currentStep === $i ? 'text-primary fw-bold' : 'text-muted' }}">
                                        @switch($i)
                                            @case(1)
                                                Header Info
                                            @break

                                            @case(2)
                                                Line Items
                                            @break

                                            @case(3)
                                                Totals & Files
                                            @break

                                            @case(4)
                                                Review
                                            @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    {{-- Progress Bar --}}
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" role="progressbar"
                            style="width: {{ $progressPercentage }}%" aria-valuenow="{{ $progressPercentage }}"
                            aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Form Card --}}
    <div class="card">
        <div class="card-body">

            {{-- Step 1: Header Information --}}
            @if ($currentStep === 1)
                @include('livewire.tickets.finance.partials.header-form')
            @endif

            {{-- Step 2: Line Items --}}
            @if ($currentStep === 2)
                @include('livewire.tickets.finance.partials.line-items')
            @endif

            {{-- Step 3: Totals, Remarks & Attachments --}}
            @if ($currentStep === 3)
                @include('livewire.tickets.finance.partials.totals-section')
            @endif

            {{-- Step 4: Review --}}
            @if ($currentStep === 4)
                @include('livewire.tickets.finance.partials.review-section')
            @endif

        </div>

        {{-- Card Footer with Navigation Buttons --}}
        <div class="card-footer bg-light border-top">
            <div class="d-flex justify-content-between align-items-center">

                {{-- Previous Button --}}
                <button type="button" wire:click="previousStep" class="btn btn-light"
                    @if ($currentStep === 1) disabled @endif>
                    <i class="mdi mdi-arrow-left me-1"></i> Previous
                </button>

                {{-- Step Info (Mobile) --}}
                <div class="d-md-none text-muted small">
                    Step {{ $currentStep }} of {{ $totalSteps }}
                </div>

                {{-- Next/Save Buttons --}}
                <div class="gap-2 d-flex">
                    @if ($currentStep < $totalSteps)
                        {{-- Next Button --}}
                        <button type="button" wire:click="nextStep" class="btn btn-primary"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="nextStep">
                                Next <i class="mdi mdi-arrow-right ms-1"></i>
                            </span>
                            <span wire:loading wire:target="nextStep">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Validating...
                            </span>
                        </button>
                    @else
                        {{-- Save as Draft Button --}}
                        <button type="button" wire:click="saveDraft" class="btn btn-secondary"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveDraft">
                                <i class="mdi mdi-content-save-outline me-1"></i> Save as Draft
                            </span>
                            <span wire:loading wire:target="saveDraft">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Saving...
                            </span>
                        </button>

                        {{-- Save & Post Button (Admin Only) --}}
                        @if (Auth::user()->isAdmin())
                            <button type="button" wire:click="saveAndPost" class="btn btn-success"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="saveAndPost">
                                    <i class="mdi mdi-check-circle me-1"></i> Save & Post
                                </span>
                                <span wire:loading wire:target="saveAndPost">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Processing...
                                </span>
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Add Modals --}}
    @if ($showQuickAddClient)
        @livewire('masters.client.quick-add-client', ['departmentId' => $department_id], key('quick-client-' . $department_id))
    @endif

    @if ($showQuickAddUOM)
        @livewire('masters.uom.quick-add-uom')
    @endif

    @if ($showQuickAddServiceType)
        @livewire('masters.service-types.quick-add-service-type', ['departmentId' => $department_id], key('quick-service-' . $department_id))
    @endif

</div>

{{-- Styles --}}
@push('styles')
    <style>
        .step-indicator {
            display: inline-block;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .step-indicator.active .step-circle {
            border-color: var(--bs-primary);
            transform: scale(1.1);
        }

        .step-label {
            margin-top: 8px;
            font-size: 0.75rem;
        }

        @media (max-width: 768px) {
            .step-circle {
                width: 32px;
                height: 32px;
                font-size: 0.875rem;
            }

            .step-label {
                font-size: 0.65rem;
            }
        }
    </style>
@endpush

{{-- AutoSave Scripts --}}
@push('scripts')
    <script>
        function financeTicketForm() {
            return {
                init() {
                    // Auto-save every 30 seconds (debounced)
                    setInterval(() => {
                        @this.call('autoSaveDraft');
                    }, 30000);

                    // Listen for restore draft confirmation
                    Livewire.on('confirm-restore-draft', (data) => {
                        if (confirm('You have an unsaved draft. Would you like to restore it?')) {
                            @this.dispatch('restore-draft', data.draft);
                        } else {
                            @this.dispatch('discard-draft');
                        }
                    });

                    // Prevent accidental page leave
                    window.addEventListener('beforeunload', (e) => {
                        if (@this.currentStep > 1) {
                            e.preventDefault();
                            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                            return e.returnValue;
                        }
                    });
                }
            }
        }
    </script>
@endpush
