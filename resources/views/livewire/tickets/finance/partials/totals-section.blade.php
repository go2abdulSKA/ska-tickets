{{-- resources/views/livewire/tickets/finance/partials/totals-section.blade.php --}}

<div class="row">
    
    {{-- Left Column: Remarks & Attachments --}}
    <div class="col-lg-6">
        
        {{-- Remarks --}}
        <div class="mb-3 card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="mdi mdi-text-box-outline me-1"></i> Remarks
                </h6>
            </div>
            <div class="card-body">
                <textarea 
                    wire:model.blur="remarks" 
                    class="form-control @error('remarks') is-invalid @enderror" 
                    rows="5"
                    placeholder="Enter any additional notes or remarks..."
                    maxlength="1000"></textarea>
                @error('remarks')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                    <span x-data="{ count: $wire.entangle('remarks').length }">
                        <span x-text="count"></span>/1000 characters
                    </span>
                </small>
            </div>
        </div>

        {{-- Attachments --}}
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="mdi mdi-paperclip me-1"></i> Attachments
                </h6>
            </div>
            <div class="card-body">
                
                {{-- File Upload --}}
                <div class="mb-3">
                    <label for="attachments" class="form-label">Upload Files</label>
                    <input type="file" 
                           wire:model="attachments" 
                           class="form-control @error('attachments.*') is-invalid @enderror" 
                           id="attachments"
                           multiple
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                    @error('attachments.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="mt-1 text-muted d-block">
                        Max 5MB per file. Supported: PDF, Images, Word, Excel
                    </small>
                </div>

                {{-- Upload Progress --}}
                <div wire:loading wire:target="attachments" class="mb-3">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             style="width: 100%">
                            Uploading...
                        </div>
                    </div>
                </div>

                {{-- New Attachments Preview --}}
                @if(!empty($attachments))
                    <div class="mb-3">
                        <p class="mb-2 small fw-bold">New Files ({{ count($attachments) }}):</p>
                        <div class="list-group">
                            @foreach($attachments as $index => $file)
                                <div class="py-2 list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <i class="mdi mdi-file-document-outline me-2 text-primary"></i>
                                        <div class="flex-grow-1">
                                            <div class="small">{{ $file->getClientOriginalName() }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                {{ number_format($file->getSize() / 1024, 2) }} KB
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            wire:click="removeAttachment({{ $index }})"
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="mdi mdi-close"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Existing Attachments (Edit Mode) --}}
                @if(!empty($existingAttachments))
                    <div>
                        <p class="mb-2 small fw-bold">Existing Files ({{ count($existingAttachments) }}):</p>
                        <div class="list-group">
                            @foreach($existingAttachments as $attachment)
                                <div class="py-2 list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <i class="{{ $attachment['icon'] }} me-2 text-info"></i>
                                        <div class="flex-grow-1">
                                            <div class="small">{{ $attachment['original_name'] }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                {{ $attachment['human_file_size'] }}
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            wire:click="removeExistingAttachment({{ $attachment['id'] }})"
                                            wire:confirm="Are you sure you want to remove this file?"
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- No Files Message --}}
                @if(empty($attachments) && empty($existingAttachments))
                    <div class="py-4 text-center text-muted">
                        <i class="mdi mdi-file-upload-outline" style="font-size: 48px; opacity: 0.3;"></i>
                        <p class="mb-0 small">No files uploaded yet</p>
                    </div>
                @endif

            </div>
        </div>

    </div>

    {{-- Right Column: Totals Calculation --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="mdi mdi-calculator me-1"></i> Totals Calculation
                </h6>
            </div>
            <div class="card-body">

                {{-- Subtotal --}}
                <div class="pb-3 mb-3 d-flex justify-content-between align-items-center border-bottom">
                    <span class="text-muted">Subtotal</span>
                    <span class="fs-5">
                        {{ \App\Enums\Currency::from($currency)->symbol() }}
                        <span wire:loading.remove>{{ number_format($subtotal, 2) }}</span>
                        <span wire:loading wire:target="calculateTotals">
                            <span class="spinner-border spinner-border-sm"></span>
                        </span>
                    </span>
                </div>

                {{-- VAT Percentage Input --}}
                <div class="mb-3">
                    <label for="vat_percentage" class="form-label">
                        VAT Percentage (%) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="number" 
                               wire:model.live="vat_percentage" 
                               class="form-control form-control-lg text-end @error('vat_percentage') is-invalid @enderror" 
                               id="vat_percentage"
                               step="0.01"
                               min="0"
                               max="100"
                               placeholder="5.00">
                        <span class="input-group-text">%</span>
                    </div>
                    @error('vat_percentage')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Default: 5%</small>
                </div>

                {{-- VAT Amount --}}
                <div class="pb-3 mb-3 d-flex justify-content-between align-items-center border-bottom">
                    <span class="text-muted">
                        VAT Amount 
                        @if($vat_percentage)
                            ({{ $vat_percentage }}%)
                        @endif
                    </span>
                    <span class="fs-5">
                        {{ \App\Enums\Currency::from($currency)->symbol() }}
                        <span wire:loading.remove>{{ number_format($vat_amount, 2) }}</span>
                        <span wire:loading wire:target="calculateTotals,vat_percentage">
                            <span class="spinner-border spinner-border-sm"></span>
                        </span>
                    </span>
                </div>

                {{-- Grand Total --}}
                <div class="p-3 rounded d-flex justify-content-between align-items-center bg-primary bg-opacity-10">
                    <span class="fw-bold fs-5">GRAND TOTAL</span>
                    <span class="fw-bold text-primary" style="font-size: 1.75rem;">
                        {{ \App\Enums\Currency::from($currency)->symbol() }}
                        <span wire:loading.remove>{{ number_format($total_amount, 2) }}</span>
                        <span wire:loading wire:target="calculateTotals,vat_percentage">
                            <span class="spinner-border"></span>
                        </span>
                    </span>
                </div>

                {{-- Calculation Breakdown --}}
                <div class="mt-3 small text-muted">
                    <p class="mb-1"><strong>Calculation:</strong></p>
                    <ul class="mb-0 ps-3">
                        <li>Subtotal: {{ number_format($subtotal, 2) }}</li>
                        <li>VAT ({{ $vat_percentage }}%): {{ number_format($vat_amount, 2) }}</li>
                        <li>Total: {{ number_format($total_amount, 2) }}</li>
                    </ul>
                </div>

            </div>
        </div>

        {{-- Currency Info Card --}}
        <div class="mt-3 card">
            <div class="py-2 card-body">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-information-outline text-info me-2"></i>
                    <small class="text-muted">
                        All amounts are in <strong>{{ \App\Enums\Currency::from($currency)->fullName() }}</strong>
                    </small>
                </div>
            </div>
        </div>

    </div>

</div>
