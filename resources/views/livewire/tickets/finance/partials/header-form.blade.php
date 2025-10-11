{{-- resources/views/livewire/tickets/finance/partials/header-form.blade.php --}}

<div class="row g-3">

    {{-- Ticket Date --}}
    <div class="col-md-4">
        <label for="ticket_date" class="form-label">
            Ticket Date <span class="text-danger">*</span>
        </label>
        <input type="date" wire:model.blur="ticket_date" class="form-control @error('ticket_date') is-invalid @enderror"
            id="ticket_date" max="{{ date('Y-m-d') }}">
        @error('ticket_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Department --}}
    <div class="col-md-4">
        <label for="department_id" class="form-label">
            Department <span class="text-danger">*</span>
        </label>
        <select wire:model.live="department_id" class="form-select @error('department_id') is-invalid @enderror"
            id="department_id" @if ($editMode) disabled @endif>
            <option value="">-- Select Department --</option>
            @foreach ($departments as $dept)
                <option value="{{ $dept->id }}">
                    {{ $dept->department }} ({{ $dept->prefix }})
                </option>
            @endforeach
        </select>
        @error('department_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Currency --}}
    <div class="col-md-4">
        <label for="currency" class="form-label">
            Currency <span class="text-danger">*</span>
        </label>
        <select wire:model.live="currency" class="form-select @error('currency') is-invalid @enderror" id="currency">
            @foreach (\App\Enums\Currency::cases() as $curr)
                <option value="{{ $curr->value }}">
                    {{ $curr->label() }} ({{ $curr->symbol() }})
                </option>
            @endforeach
        </select>
        @error('currency')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Divider --}}
    <div class="col-12">
        <hr class="my-2">
    </div>
    <div class="col-12">
        <h6 class="mb-0">Customer Information</h6>
        <p class="mb-2 text-muted small">Select whether this ticket is for an external client or internal cost center
        </p>
    </div>

    {{-- Client Type Toggle --}}
    <div class="col-12">
        <div class="btn-group w-100" role="group">
            <input type="radio" wire:model.live="client_type" value="client" class="btn-check" id="client_type_client"
                autocomplete="off">
            <label class="btn btn-outline-primary" for="client_type_client">
                <i class="mdi mdi-account-multiple me-1"></i> External Client
            </label>

            <input type="radio" wire:model.live="client_type" value="cost_center" class="btn-check"
                id="client_type_cost_center" autocomplete="off">
            <label class="btn btn-outline-primary" for="client_type_cost_center">
                <i class="mdi mdi-office-building me-1"></i> Cost Center
            </label>
        </div>
    </div>

    {{-- Client Selection --}}
    @if ($client_type === 'client')
        <div class="col-md-6">
            <label for="client_id" class="form-label">
                Client <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                @php
                    $clientOptions = [];
                    if ($department_id && $clients) {
                        $clientOptions = $clients
                            ->map(function ($client) {
                                return [
                                    'value' => $client->id,
                                    'label' =>
                                        $client->client_name .
                                        ($client->company_name ? ' - ' . $client->company_name : ''),
                                ];
                            })
                            ->toArray();
                    }
                @endphp

                <x-ui.searchable-select id="client_id" wire-model="client_id" :options="$clientOptions"
                    placeholder="{{ !$department_id ? 'Select Department First' : 'Search clients...' }}"
                    :disabled="!$department_id" />

                <button type="button" wire:click="openQuickAddClient" class="btn btn-primary" title="Add New Client"
                    @if (!$department_id) disabled @endif>
                    <i class="mdi mdi-plus"></i>
                </button>
            </div>
            @error('client_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            @if (!$department_id)
                <small class="text-muted">Please select a department first</small>
            @endif
        </div>
    @endif

    {{-- Cost Center Selection --}}
    @if ($client_type === 'cost_center')
        <div class="col-md-6">
            <label for="cost_center_id" class="form-label">
                Cost Center <span class="text-danger">*</span>
            </label>
            @php
                $costCenterOptions = [];
                if ($costCenters) {
                    $costCenterOptions = $costCenters
                        ->map(function ($cc) {
                            return [
                                'value' => $cc->id,
                                'label' => $cc->code . ' - ' . $cc->name,
                            ];
                        })
                        ->toArray();
                }
            @endphp

            <x-ui.searchable-select id="cost_center_id" wire-model="cost_center_id" :options="$costCenterOptions"
                {{-- placeholder="Search cost centers..." /> --}}
                placeholder="{{ !$department_id ? 'Select Department First' : 'Search cost centers...' }}"
                :disabled="!$department_id" />

            @error('cost_center_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            @if (!$department_id)
                <small class="text-muted">Please select a department first</small>
            @endif

        </div>
    @endif

    {{-- Service Type Selection --}}
    <div class="col-md-6">
        <label for="service_type_id" class="form-label">
            Service Type
        </label>
        <div class="input-group">
            @php
                $serviceTypeOptions = [];
                if ($department_id && $serviceTypes) {
                    $serviceTypeOptions = $serviceTypes
                        ->map(function ($st) {
                            return [
                                'value' => $st->id,
                                'label' => $st->service_type,
                            ];
                        })
                        ->toArray();
                }
            @endphp

            <x-ui.searchable-select id="service_type_id" wire-model="service_type_id" :options="$serviceTypeOptions"
                placeholder="{{ !$department_id ? 'Select Department First' : 'Search service types...' }}"
                :disabled="!$department_id" />

            <button type="button" wire:click="openQuickAddServiceType" class="btn btn-primary"
                title="Add New Service Type" @if (!$department_id) disabled @endif>
                <i class="mdi mdi-plus"></i>
            </button>
        </div>
        @error('service_type_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        @if (!$department_id)
            <small class="text-muted">Please select a department first</small>
        @endif
    </div>

    {{-- Divider --}}
    <div class="col-12">
        <hr class="my-2">
    </div>
    <div class="col-12">
        <h6 class="mb-0">Project Details</h6>
    </div>

    {{-- Project Code --}}
    <div class="col-md-4">
        <label for="project_code" class="form-label">Project Code</label>
        <input type="text" wire:model.blur="project_code"
            class="form-control @error('project_code') is-invalid @enderror" id="project_code"
            placeholder="e.g., PROJ-2025-001" maxlength="100">
        @error('project_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Contract No --}}
    <div class="col-md-4">
        <label for="contract_no" class="form-label">Contract No</label>
        <input type="text" wire:model.blur="contract_no"
            class="form-control @error('contract_no') is-invalid @enderror" id="contract_no"
            placeholder="e.g., CTR-2025-001" maxlength="100">
        @error('contract_no')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Service Location --}}
    <div class="col-md-4">
        <label for="service_location" class="form-label">Service Location</label>
        <input type="text" wire:model.blur="service_location"
            class="form-control @error('service_location') is-invalid @enderror" id="service_location"
            placeholder="e.g., Mogadishu, Somalia" maxlength="100">
        @error('service_location')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Divider --}}
    <div class="col-12">
        <hr class="my-2">
    </div>
    <div class="col-12">
        <h6 class="mb-0">Payment Information</h6>
    </div>

    {{-- Payment Type --}}
    <div class="col-md-4">
        <label for="payment_type" class="form-label">
            Payment Type <span class="text-danger">*</span>
        </label>
        <select wire:model.live="payment_type" class="form-select @error('payment_type') is-invalid @enderror"
            id="payment_type">
            @foreach (\App\Enums\PaymentType::cases() as $pt)
                <option value="{{ $pt->value }}">{{ $pt->label() }}</option>
            @endforeach
        </select>
        @error('payment_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Payment Terms --}}
    <div class="col-md-4">
        <label for="payment_terms" class="form-label">Payment Terms</label>
        <input type="text" wire:model.blur="payment_terms"
            class="form-control @error('payment_terms') is-invalid @enderror" id="payment_terms"
            placeholder="e.g., Net 30 days" maxlength="100">
        @error('payment_terms')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Reference No --}}
    <div class="col-md-4">
        <label for="ref_no" class="form-label">Reference No</label>
        {{-- <input type="text" value='oldvalue' wire:model.blur="ref_no" class="form-control @error('ref_no') is-invalid @enderror" --}}
        <input type="text" wire:model.blur="ref_no" id="ref_no" placeholder="e.g., PO-2025-001"
            maxlength="100">
        @error('ref_no')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

{{-- @push('HeadTop')
    <!-- Choices Plugin CSS-->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/choices/choices.min.css') }}" />

    <!-- Select Plugin CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/select2.min.css') }}" />
@endpush --}}

{{-- @push('scripts')
    <!-- Jquery for select2-->
    <script src="{{ asset('backend/assets/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Select2 Plugin Js -->
    <script src="{{ asset('backend/assets/plugins/select2/select2.min.js') }}"></script>

    <script>
        // Track all Select2 instances
        window.select2Instances = window.select2Instances || {};

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initializeAllSelects, 100);
        });

        // Reinitialize after Livewire DOM updates
        document.addEventListener('livewire:update', function() {
            setTimeout(initializeAllSelects, 200);
        });

        function initializeAllSelects() {
            if (typeof jQuery === 'undefined' || typeof jQuery.fn.select2 === 'undefined') {
                console.error('jQuery or Select2 not loaded');
                return;
            }

            console.log('🔄 Initializing Select2 dropdowns...');

            jQuery('[data-toggle="select2"]').each(function() {
                var $element = jQuery(this);
                var elementId = $element.attr('id');

                if (!elementId) {
                    console.warn('⚠️ Select element without ID found, skipping');
                    return;
                }

                // Skip if disabled
                if ($element.is(':disabled')) {
                    console.log('⏭️ Skipping disabled:', elementId);
                    if (window.select2Instances[elementId]) {
                        try {
                            $element.select2('destroy');
                            delete window.select2Instances[elementId];
                        } catch (e) {}
                    }
                    return;
                }

                // Check if already initialized and working
                if (window.select2Instances[elementId] && $element.hasClass('select2-hidden-accessible')) {
                    console.log('✅ Already initialized:', elementId);
                    return;
                }

                // Destroy if partially initialized
                if ($element.hasClass('select2-hidden-accessible')) {
                    try {
                        $element.select2('destroy');
                    } catch (e) {}
                }

                // Initialize Select2
                $element.select2({
                    theme: 'bootstrap5',
                    placeholder: $element.data('placeholder') || 'Select an option',
                    allowClear: true,
                    width: '100%'
                });

                // Track this instance
                window.select2Instances[elementId] = true;
                console.log('✨ Initialized:', elementId);

                // Bind change event to sync with Livewire
                $element.off('select2:select').on('select2:select', function(e) {
                    var value = $element.val();
                    syncWithLivewire(elementId, value);
                });

                $element.off('select2:clear').on('select2:clear', function(e) {
                    syncWithLivewire(elementId, null);
                });
            });

            console.log('📊 Total Select2 instances:', Object.keys(window.select2Instances).length);
        }

        function syncWithLivewire(elementId, value) {
            console.log('🔄 Syncing', elementId, 'with value:', value);

            if (elementId === 'client_id') {
                @this.set('client_id', value);
            } else if (elementId === 'cost_center_id') {
                @this.set('cost_center_id', value);
            } else if (elementId === 'service_type_id') {
                @this.set('service_type_id', value);
            }
        }

        // Listen for events
        if (typeof Livewire !== 'undefined') {
            Livewire.on('client-created', () => {
                setTimeout(initializeAllSelects, 300);
            });

            Livewire.on('service-type-created', () => {
                setTimeout(initializeAllSelects, 300);
            });
        }
    </script>
@endpush --}}


{{-- @push('styles')
    <style>
        /* =========================
           SELECT2 DARK THEME
           ========================= */

        /* Base Container */
        .select2-container {
            width: 100% !important;
        }

        /* Selection Box */
        .select2-container--bootstrap5 .select2-selection {
            height: calc(1.5em + 0.9rem + 2px) !important;
            border: 1px solid #404954 !important;
            background-color: #37404a !important;
            color: #aab8c5 !important;
            border-radius: 0.25rem !important;
            display: flex;
            align-items: center;
        }

        /* Selected Text */
        .select2-container--bootstrap5 .select2-selection__rendered {
            line-height: calc(1.5em + 0.9rem) !important;
            padding: 0 0.9rem !important;
            color: #aab8c5 !important;
        }

        /* Placeholder */
        .select2-container--bootstrap5 .select2-selection__placeholder {
            color: #6c757d !important;
        }

        /* Arrow */
        .select2-container--bootstrap5 .select2-selection__arrow {
            height: 100% !important;
            right: 0.45rem !important;
        }

        .select2-container--bootstrap5 .select2-selection__arrow b {
            border-color: #aab8c5 transparent transparent transparent !important;
        }

        /* Clear Button */
        .select2-container--bootstrap5 .select2-selection__clear {
            color: #aab8c5 !important;
            margin-right: 10px;
        }

        /* Focus State */
        .select2-container--bootstrap5.select2-container--focus .select2-selection,
        .select2-container--bootstrap5.select2-container--open .select2-selection {
            border-color: #727cf5 !important;
            box-shadow: 0 0 0 0.2rem rgba(114, 124, 245, 0.25) !important;
        }

        /* Disabled State */
        .select2-container--bootstrap5 .select2-selection--single[aria-disabled=true] {
            background-color: #2c333b !important;
            cursor: not-allowed !important;
            opacity: 0.6;
        }

        /* Dropdown */
        .select2-dropdown {
            background-color: #37404a !important;
            border: 1px solid #404954 !important;
            border-radius: 0.25rem;
            z-index: 1056 !important;
        }

        /* Search Box */
        .select2-search--dropdown {
            padding: 8px;
            background-color: #37404a;
        }

        .select2-search--dropdown .select2-search__field {
            background-color: #2c333b !important;
            border: 1px solid #404954 !important;
            color: #aab8c5 !important;
            padding: 0.45rem 0.9rem;
            border-radius: 0.25rem;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #727cf5 !important;
            outline: none;
        }

        /* Results List */
        .select2-results {
            background-color: #37404a !important;
        }

        .select2-results__options {
            background-color: #37404a !important;
        }

        .select2-results__option {
            padding: 8px 12px !important;
            color: #aab8c5 !important;
            background-color: #37404a !important;
        }

        /* Highlighted Option */
        .select2-results__option--highlighted {
            background-color: #727cf5 !important;
            color: #ffffff !important;
        }

        /* Selected Option */
        .select2-results__option[aria-selected=true] {
            background-color: #2c333b !important;
            color: #727cf5 !important;
        }

        .select2-results__option[aria-selected=true]:hover {
            background-color: #727cf5 !important;
            color: #ffffff !important;
        }

        /* No Results */
        .select2-results__message {
            color: #6c757d !important;
            padding: 12px;
        }

        /* Input Group Adjustments */
        .input-group .select2-container {
            flex: 1 1 auto;
            width: 1% !important;
        }

        .input-group .select2-selection {
            border-right: 0 !important;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        /* Fix alignment */
        .input-group {
            align-items: stretch;
        }

        .input-group>* {
            margin: 0 !important;
        }
    </style>
@endpush --}}
