{{-- resources/views/livewire/tickets/finance/partials/ticket-card-mobile.blade.php --}}

<div class="mb-3 shadow-sm card">
    <div class="card-body">
        
        {{-- Header Row --}}
        <div class="mb-3 d-flex justify-content-between align-items-start">
            <div>
                <a href="javascript:void(0);" 
                   wire:click="view({{ $ticket->id }})"
                   class="text-decoration-none">
                    <h6 class="mb-1 text-primary">{{ $ticket->ticket_no }}</h6>
                </a>
                <small class="text-muted">{{ $ticket->ticket_date->format('d M, Y') }}</small>
            </div>
            <span class="badge {{ $ticket->status->badgeClass() }}">
                {{ $ticket->status->label() }}
            </span>
        </div>

        {{-- Customer Info --}}
        <div class="mb-3">
            <label class="mb-1 text-muted small d-block">Customer</label>
            <div class="fw-semibold">{{ $ticket->customer_name }}</div>
            <span class="badge badge-soft-{{ $ticket->client_type->value === 'client' ? 'primary' : 'info' }} badge-sm mt-1">
                {{ $ticket->client_type->label() }}
            </span>
        </div>

        {{-- Project & Amount Row --}}
        <div class="mb-3 row">
            @if($ticket->project_code)
                <div class="col-6">
                    <label class="mb-1 text-muted small d-block">Project</label>
                    <span class="badge badge-soft-secondary">{{ $ticket->project_code }}</span>
                </div>
            @endif
            <div class="col-{{ $ticket->project_code ? '6' : '12' }}">
                <label class="mb-1 text-muted small d-block">Amount</label>
                <strong class="text-primary fs-5">
                    {{ $ticket->currency->symbol() }}{{ number_format($ticket->total_amount, 2) }}
                </strong>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="gap-2 d-grid">
            <div class="btn-group">
                <button wire:click="view({{ $ticket->id }})"
                        class="btn btn-sm btn-outline-primary">
                    <i class="mdi mdi-eye me-1"></i> View
                </button>
                
                @if($ticket->canEdit())
                    <a href="{{ route('tickets.finance.edit', $ticket->id) }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-pencil me-1"></i> Edit
                    </a>
                @endif

                <a href="{{ route('tickets.finance.duplicate', $ticket->id) }}"
                   class="btn btn-sm btn-outline-info">
                    <i class="mdi mdi-content-copy me-1"></i> Duplicate
                </a>

                @if($ticket->canDelete())
                    <button wire:click="confirmDelete({{ $ticket->id }})"
                            class="btn btn-sm btn-outline-danger">
                        <i class="mdi mdi-delete me-1"></i> Delete
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
