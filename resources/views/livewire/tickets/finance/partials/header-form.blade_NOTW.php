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
                <option value="{{ $dept->id }}">{{ $dept->department }} ({{ $dept->prefix }})</option>
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
                <select wire:model.live="client_id" class="form-select @error('client_id') is-invalid @enderror"
                    id="client_id" @if (!$department_id) disabled @endif>
                    <option value="">
                        @if (!$department_id)
                            -- Select Department First --
                        @else
                            -- Select Client --
                        @endif
                    </option>
                    @if ($department_id)
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->client_name }}
                                @if ($client->company_name)
                                    - {{ $client->company_name }}
                                @endif
                            </option>
                        @endforeach
                    @endif
                </select>
                <button type="button" wire:click="openQuickAddClient" class="btn btn-outline-primary"
                    title="Add New Client" @if (!$department_id) disabled @endif>
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

    {{-- Cost Center Selection (already correct) --}}
    @if ($client_type === 'cost_center')
        <div class="col-md-6">
            <label for="cost_center_id" class="form-label">
                Cost Center <span class="text-danger">*</span>
            </label>
            <select wire:model.live="cost_center_id" class="form-select @error('cost_center_id') is-invalid @enderror"
                id="cost_center_id">
                <option value="">-- Select Cost Center --</option>
                @foreach ($costCenters as $cc)
                    <option value="{{ $cc->id }}">{{ $cc->code }} - {{ $cc->name }}</option>
                @endforeach
            </select>
            @error('cost_center_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endif

    {{-- Service Type --}}
    <div class="col-md-6">
        <label for="service_type_id" class="form-label">
            Service Type
        </label>
        <div class="input-group">
            <select wire:model.live="service_type_id" class="form-select @error('service_type_id') is-invalid @enderror"
                id="service_type_id" @if (!$department_id) disabled @endif>
                <option value="">
                    @if (!$department_id)
                        -- Select Department First --
                    @else
                        -- Select Service Type --
                    @endif
                </option>
                @if ($department_id)
                    @foreach ($serviceTypes as $st)
                        <option value="{{ $st->id }}">{{ $st->service_type }}</option>
                    @endforeach
                @endif
            </select>
            <button type="button" wire:click="openQuickAddServiceType" class="btn btn-outline-primary"
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

    {{-- Client Selection --}}
    {{-- @if ($client_type === 'client')
        <div class="col-md-6">
            <label for="client_id" class="form-label">
                Client <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <select wire:model.live="client_id"
                        class="form-select @error('client_id') is-invalid @enderror"
                        id="client_id">
                    <option value="">-- Select Client --</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">
                            {{ $client->client_name }}
                            @if ($client->company_name)
                                - {{ $client->company_name }}
                            @endif
                        </option>
                    @endforeach
                </select>
                <button type="button"
                        wire:click="openQuickAddClient"
                        class="btn btn-outline-primary"
                        title="Add New Client">
                    <i class="mdi mdi-plus"></i>
                </button>
            </div>
            @error('client_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    @endif --}}

    {{-- Cost Center Selection --}}
    {{-- @if ($client_type === 'cost_center')
        <div class="col-md-6">
            <label for="cost_center_id" class="form-label">
                Cost Center <span class="text-danger">*</span>
            </label>
            <select wire:model.live="cost_center_id"
                    class="form-select @error('cost_center_id') is-invalid @enderror"
                    id="cost_center_id">
                <option value="">-- Select Cost Center --</option>
                @foreach ($costCenters as $cc)
                    <option value="{{ $cc->id }}">{{ $cc->code }} - {{ $cc->name }}</option>
                @endforeach
            </select>
            @error('cost_center_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endif --}}

    {{-- Service Type --}}
    {{-- <div class="col-md-6">
        <label for="service_type_id" class="form-label">
            Service Type
        </label>
        <div class="input-group">
            <select wire:model.live="service_type_id"
                    class="form-select @error('service_type_id') is-invalid @enderror"
                    id="service_type_id">
                <option value="">-- Select Service Type --</option>
                @foreach ($serviceTypes as $st)
                    <option value="{{ $st->id }}">{{ $st->service_type }}</option>
                @endforeach
            </select>
            <button type="button"
                    wire:click="openQuickAddServiceType"
                    class="btn btn-outline-primary"
                    title="Add New Service Type"
                    @if (!$department_id) disabled @endif>
                <i class="mdi mdi-plus"></i>
            </button>
        </div>
        @error('service_type_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div> --}}


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
        <input type="text" wire:model.blur="ref_no" class="form-control @error('ref_no') is-invalid @enderror"
            id="ref_no" placeholder="e.g., PO-2025-001" maxlength="100">
        @error('ref_no')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>
