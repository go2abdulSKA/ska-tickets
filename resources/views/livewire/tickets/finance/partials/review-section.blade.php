{{-- resources/views/livewire/tickets/finance/partials/review-section.blade.php --}}

<div>
    
    {{-- Review Header --}}
    <div class="mb-4 alert alert-info">
        <i class="mdi mdi-check-circle-outline me-2"></i>
        <strong>Review Your Ticket</strong> - Please review all information before saving
    </div>

    {{-- Header Information --}}
    <div class="mb-3 card">
        <div class="card-header bg-light">
            <h6 class="mb-0">Header Information</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Ticket Number:</strong>
                        <span class="badge badge-soft-primary">{{ $previewTicketNumber }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Date:</strong>
                        <span>{{ \Carbon\Carbon::parse($ticket_date)->format('d M, Y') }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Department:</strong>
                        <span>{{ $departments->find($department_id)?->department ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Currency:</strong>
                        <span>{{ \App\Enums\Currency::from($currency)->fullName() }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Customer Type:</strong>
                        <span class="badge badge-soft-{{ $client_type === 'client' ? 'primary' : 'info' }}">
                            {{ \App\Enums\ClientType::from($client_type)->label() }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Customer:</strong>
                        <span>
                            @if($client_type === 'client')
                                {{ $clients->find($client_id)?->full_name ?? 'N/A' }}
                            @else
                                {{ $costCenters->find($cost_center_id)?->full_name ?? 'N/A' }}
                            @endif
                        </span>
                    </div>
                </div>

                @if($service_type_id)
                    <div class="col-md-6">
                        <div class="d-flex">
                            <strong class="text-muted" style="min-width: 150px;">Service Type:</strong>
                            <span>{{ $serviceTypes->find($service_type_id)?->service_type ?? 'N/A' }}</span>
                        </div>
                    </div>
                @endif

                @if($project_code)
                    <div class="col-md-6">
                        <div class="d-flex">
                            <strong class="text-muted" style="min-width: 150px;">Project Code:</strong>
                            <span>{{ $project_code }}</span>
                        </div>
                    </div>
                @endif

                @if($contract_no)
                    <div class="col-md-6">
                        <div class="d-flex">
                            <strong class="text-muted" style="min-width: 150px;">Contract No:</strong>
                            <span>{{ $contract_no }}</span>
                        </div>
                    </div>
                @endif

                @if($service_location)
                    <div class="col-md-6">
                        <div class="d-flex">
                            <strong class="text-muted" style="min-width: 150px;">Service Location:</strong>
                            <span>{{ $service_location }}</span>
                        </div>
                    </div>
                @endif

                <div class="col-md-6">
                    <div class="d-flex">
                        <strong class="text-muted" style="min-width: 150px;">Payment Type:</strong>
                        <span>{{ \App\Enums\PaymentType::from($payment_type)->label() }}</span>
                    </div>
                </div>

                @if($payment_terms)
                    <div class="col-md-6">
                        <div class="d-flex">
                            <strong class="text-muted" style="min-width: 150px;">Payment Terms:</strong>
                            <span>{{ $payment_terms }}</span>
                        </div>
                    </div>
                @endif

                @if($ref_no)
                    <div class="col-md-6">
                        <div class="d-flex">
                            <strong class="text-muted" style="min-width: 150px;">Reference No:</strong>
                            <span>{{ $ref_no }}</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Edit Header Button --}}
            <div class="mt-3">
                <button type="button" 
                        wire:click="goToStep(1)" 
                        class="btn btn-sm btn-outline-primary">
                    <i class="mdi mdi-pencil me-1"></i> Edit Header Info
                </button>
            </div>
        </div>
    </div>

    {{-- Line Items --}}
    <div class="mb-3 card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Line Items ({{ count($transactions) }})</h6>
            <button type="button" 
                    wire:click="goToStep(2)" 
                    class="btn btn-sm btn-outline-primary">
                <i class="mdi mdi-pencil me-1"></i> Edit Line Items
            </button>
        </div>
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table mb-0 table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Description</th>
                            <th class="text-end" style="width: 100px;">Qty</th>
                            <th style="width: 80px;">UOM</th>
                            <th class="text-end" style="width: 120px;">Unit Cost</th>
                            <th class="text-end" style="width: 120px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $item)
                            <tr>
                                <td class="text-center">{{ $item['sr_no'] }}</td>
                                <td>{{ $item['description'] }}</td>
                                <td class="text-end">{{ number_format($item['qty'], 3) }}</td>
                                <td>
                                    @php
                                        $uom = $uoms->find($item['uom_id']);
                                    @endphp
                                    {{ $uom?->code ?? 'N/A' }}
                                </td>
                                <td class="text-end">{{ number_format($item['unit_cost'], 2) }}</td>
                                <td class="text-end fw-bold">{{ number_format($item['total_cost'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Totals & Remarks --}}
    <div class="row">
        <div class="col-lg-6">
            {{-- Remarks --}}
            @if($remarks)
                <div class="mb-3 card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Remarks</h6>
                        <button type="button" 
                                wire:click="goToStep(3)" 
                                class="btn btn-sm btn-outline-primary">
                            <i class="mdi mdi-pencil me-1"></i> Edit
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted">{{ $remarks }}</p>
                    </div>
                </div>
            @endif

            {{-- Attachments --}}
            @if(!empty($attachments) || !empty($existingAttachments))
                <div class="mb-3 card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            Attachments ({{ count($attachments) + count($existingAttachments) }})
                        </h6>
                        <button type="button" 
                                wire:click="goToStep(3)" 
                                class="btn btn-sm btn-outline-primary">
                            <i class="mdi mdi-pencil me-1"></i> Edit
                        </button>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0 list-unstyled">
                            @foreach($attachments as $file)
                                <li class="mb-2">
                                    <i class="mdi mdi-paperclip text-primary me-1"></i>
                                    {{ $file->getClientOriginalName() }}
                                    <span class="text-muted small">({{ number_format($file->getSize() / 1024, 2) }} KB)</span>
                                </li>
                            @endforeach
                            @foreach($existingAttachments as $att)
                                <li class="mb-2">
                                    <i class="{{ $att['icon'] }} text-info me-1"></i>
                                    {{ $att['original_name'] }}
                                    <span class="text-muted small">({{ $att['human_file_size'] }})</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-6">
            {{-- Totals Summary --}}
            <div class="mb-3 card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Totals Summary</h6>
                    <button type="button" 
                            wire:click="goToStep(3)" 
                            class="btn btn-sm btn-outline-primary">
                        <i class="mdi mdi-pencil me-1"></i> Edit
                    </button>
                </div>
                <div class="card-body">
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <span class="fw-bold">
                            {{ \App\Enums\Currency::from($currency)->symbol() }}{{ number_format($subtotal, 2) }}
                        </span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>VAT ({{ $vat_percentage }}%):</span>
                        <span class="fw-bold">
                            {{ \App\Enums\Currency::from($currency)->symbol() }}{{ number_format($vat_amount, 2) }}
                        </span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fs-5 fw-bold">GRAND TOTAL:</span>
                        <span class="fs-4 fw-bold text-primary">
                            {{ \App\Enums\Currency::from($currency)->symbol() }}{{ number_format($total_amount, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Final Actions Info --}}
    <div class="alert alert-success">
        <h6 class="alert-heading">
            <i class="mdi mdi-check-circle me-1"></i> Ready to Save
        </h6>
        <p class="mb-0">
            Click <strong>"Save as Draft"</strong> to save without posting, or 
            @if(Auth::user()->isAdmin())
                <strong>"Save & Post"</strong> to post immediately to ERP.
            @else
                submit for approval.
            @endif
        </p>
    </div>

</div>
