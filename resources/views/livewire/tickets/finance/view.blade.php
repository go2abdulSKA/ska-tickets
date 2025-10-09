{{-- resources/views/livewire/tickets/finance/view.blade.php --}}

<div class="offcanvas offcanvas-end show" 
     style="visibility: visible; width: 600px;" 
     tabindex="-1">
    
    {{-- Offcanvas Header --}}
    <div class="offcanvas-header border-bottom">
        <div>
            <h5 class="mb-1 offcanvas-title">Ticket Details</h5>
            <p class="mb-0 text-muted small">
                <span class="badge {{ $ticket->status->badgeClass() }}">
                    {{ $ticket->status->label() }}
                </span>
                <span class="ms-2">{{ $ticket->ticket_no }}</span>
            </p>
        </div>
        <button type="button" 
                class="btn-close" 
                wire:click="closeOffcanvas"></button>
    </div>
    
    {{-- Offcanvas Body --}}
    <div class="p-0 offcanvas-body">
        
        {{-- Action Buttons --}}
        <div class="p-3 bg-light border-bottom">
            <div class="gap-2 d-grid">
                {{-- Edit Button --}}
                @if($ticket->canEdit())
                    <button type="button" 
                            wire:click="edit" 
                            class="btn btn-primary btn-sm">
                        <i class="mdi mdi-pencil me-1"></i> Edit Ticket
                    </button>
                @endif

                {{-- Duplicate Button --}}
                <button type="button" 
                        wire:click="duplicate" 
                        class="btn btn-outline-secondary btn-sm">
                    <i class="mdi mdi-content-copy me-1"></i> Duplicate Ticket
                </button>

                {{-- Download PDF Button --}}
                <button type="button" 
                        wire:click="downloadPDF" 
                        class="btn btn-outline-primary btn-sm"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="downloadPDF">
                        <i class="mdi mdi-file-pdf-box me-1"></i> Download PDF
                    </span>
                    <span wire:loading wire:target="downloadPDF">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Generating...
                    </span>
                </button>
            </div>
        </div>

        {{-- Ticket Information --}}
        <div class="p-3">
            
            {{-- Header Information --}}
            <h6 class="mb-3 text-uppercase text-muted">
                <i class="mdi mdi-information-outline me-1"></i> Header Information
            </h6>

            <div class="mb-4 row g-2">
                <div class="col-6">
                    <small class="text-muted d-block">Ticket No</small>
                    <strong>{{ $ticket->ticket_no }}</strong>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Date</small>
                    <strong>{{ $ticket->ticket_date->format('d M, Y') }}</strong>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Department</small>
                    <strong>{{ $ticket->department->department }}</strong>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Created By</small>
                    <strong>{{ $ticket->user->name }}</strong>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Customer Type</small>
                    <span class="badge badge-soft-{{ $ticket->client_type->value === 'client' ? 'primary' : 'info' }}">
                        {{ $ticket->client_type->label() }}
                    </span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Customer</small>
                    <strong>{{ $ticket->customer_name }}</strong>
                </div>

                @if($ticket->service_type)
                    <div class="col-12">
                        <small class="text-muted d-block">Service Type</small>
                        <strong>{{ $ticket->serviceType->service_type }}</strong>
                    </div>
                @endif

                @if($ticket->project_code)
                    <div class="col-6">
                        <small class="text-muted d-block">Project Code</small>
                        <strong>{{ $ticket->project_code }}</strong>
                    </div>
                @endif

                @if($ticket->contract_no)
                    <div class="col-6">
                        <small class="text-muted d-block">Contract No</small>
                        <strong>{{ $ticket->contract_no }}</strong>
                    </div>
                @endif

                @if($ticket->service_location)
                    <div class="col-12">
                        <small class="text-muted d-block">Service Location</small>
                        <strong>{{ $ticket->service_location }}</strong>
                    </div>
                @endif

                <div class="col-6">
                    <small class="text-muted d-block">Payment Type</small>
                    <strong>{{ $ticket->payment_type->label() }}</strong>
                </div>

                @if($ticket->payment_terms)
                    <div class="col-6">
                        <small class="text-muted d-block">Payment Terms</small>
                        <strong>{{ $ticket->payment_terms }}</strong>
                    </div>
                @endif

                @if($ticket->ref_no)
                    <div class="col-6">
                        <small class="text-muted d-block">Reference No</small>
                        <strong>{{ $ticket->ref_no }}</strong>
                    </div>
                @endif
            </div>

            <hr class="my-3">

            {{-- Line Items --}}
            <h6 class="mb-3 text-uppercase text-muted">
                <i class="mdi mdi-format-list-bulleted me-1"></i> Line Items ({{ $ticket->transactions->count() }})
            </h6>

            <div class="mb-4 table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 30px;">#</th>
                            <th>Description</th>
                            <th class="text-end" style="width: 70px;">Qty</th>
                            <th style="width: 60px;">UOM</th>
                            <th class="text-end" style="width: 100px;">Unit Cost</th>
                            <th class="text-end" style="width: 100px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ticket->transactions as $trans)
                            <tr>
                                <td class="text-center">{{ $trans->sr_no }}</td>
                                <td>
                                    <small>{{ $trans->description }}</small>
                                </td>
                                <td class="text-end">{{ number_format($trans->qty, 3) }}</td>
                                <td>{{ $trans->uom->code }}</td>
                                <td class="text-end">{{ number_format($trans->unit_cost, 2) }}</td>
                                <td class="text-end fw-bold">{{ number_format($trans->total_cost, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr class="my-3">

            {{-- Totals --}}
            <h6 class="mb-3 text-uppercase text-muted">
                <i class="mdi mdi-calculator me-1"></i> Totals
            </h6>

            <div class="mb-4 card bg-light">
                <div class="p-3 card-body">
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <strong>{{ $ticket->currency->symbol() }}{{ number_format($ticket->subtotal, 2) }}</strong>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>VAT ({{ $ticket->vat_percentage }}%):</span>
                        <strong>{{ $ticket->currency->symbol() }}{{ number_format($ticket->vat_amount, 2) }}</strong>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">GRAND TOTAL:</span>
                        <span class="fw-bold text-primary fs-5">
                            {{ $ticket->currency->symbol() }}{{ number_format($ticket->total_amount, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Remarks --}}
            @if($ticket->remarks)
                <hr class="my-3">
                <h6 class="mb-3 text-uppercase text-muted">
                    <i class="mdi mdi-text-box-outline me-1"></i> Remarks
                </h6>
                <div class="mb-4 alert alert-info">
                    <small>{{ $ticket->remarks }}</small>
                </div>
            @endif

            {{-- Attachments --}}
            @if($ticket->attachments->count() > 0)
                <hr class="my-3">
                <h6 class="mb-3 text-uppercase text-muted">
                    <i class="mdi mdi-paperclip me-1"></i> Attachments ({{ $ticket->attachments->count() }})
                </h6>
                <div class="mb-4 list-group">
                    @foreach($ticket->attachments as $attachment)
                        <a href="javascript:void(0);" 
                           wire:click="downloadAttachment({{ $attachment->id }})"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="{{ $attachment->icon }} me-2 fs-4"></i>
                                <div>
                                    <div class="small fw-bold">{{ $attachment->original_name }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ $attachment->human_file_size }}
                                    </div>
                                </div>
                            </div>
                            <i class="mdi mdi-download"></i>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Status History --}}
            @if($ticket->statusHistory->count() > 0)
                <hr class="my-3">
                <h6 class="mb-3 text-uppercase text-muted">
                    <i class="mdi mdi-history me-1"></i> Status History
                </h6>
                <div class="mb-4 timeline">
                    @foreach($ticket->statusHistory as $history)
                        <div class="mb-3 timeline-item">
                            <div class="d-flex">
                                <div class="me-3">
                                    <div class="timeline-icon bg-{{ $history->to_status === 'posted' ? 'success' : ($history->to_status === 'cancelled' ? 'danger' : 'warning') }}">
                                        <i class="{{ \App\Enums\TicketStatus::from($history->to_status)->iconClass() }} text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ \App\Enums\TicketStatus::from($history->to_status)->label() }}</strong>
                                        <small class="text-muted">
                                            {{ $history->changed_at->format('d M, Y h:i A') }}
                                        </small>
                                    </div>
                                    <small class="text-muted">
                                        By: {{ $history->changedBy->name }}
                                    </small>
                                    @if($history->notes)
                                        <div class="mt-1 small text-muted">
                                            {{ $history->notes }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Audit Information --}}
            <hr class="my-3">
            <h6 class="mb-3 text-uppercase text-muted">
                <i class="mdi mdi-shield-check-outline me-1"></i> Audit Information
            </h6>
            <div class="small">
                <div class="mb-2">
                    <strong>Created:</strong> {{ $ticket->created_at->format('d M, Y h:i A') }}
                    <span class="text-muted">by {{ $ticket->creator->name }}</span>
                </div>
                @if($ticket->updated_at != $ticket->created_at)
                    <div class="mb-2">
                        <strong>Last Updated:</strong> {{ $ticket->updated_at->format('d M, Y h:i A') }}
                        <span class="text-muted">by {{ $ticket->updater->name }}</span>
                    </div>
                @endif
                @if($ticket->status === \App\Enums\TicketStatus::POSTED)
                    <div class="mb-2">
                        <strong>Posted:</strong> {{ $ticket->posted_date->format('d M, Y h:i A') }}
                    </div>
                @endif
                <div class="mb-2">
                    <strong>IP Address:</strong> {{ $ticket->host_name }}
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Backdrop --}}
<div class="offcanvas-backdrop fade show" wire:click="closeOffcanvas"></div>

@push('styles')
<style>
    .timeline-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush
