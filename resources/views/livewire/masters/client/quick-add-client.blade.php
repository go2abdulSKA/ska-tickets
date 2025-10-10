{{-- resources/views/livewire/masters/client/quick-add-client.blade.php --}}

<div>

    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                {{-- Modal Header --}}
                <div class="text-white modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="mdi mdi-account-plus me-2"></i>
                        Quick Add Client
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                </div>

                {{-- Modal Body --}}
                <form wire:submit.prevent="save">
                    <div class="modal-body">

                        {{-- Department Info (Read-only) --}}
                        @if ($department)
                            <div class="mb-3 alert alert-info">
                                <i class="mdi mdi-information-outline me-1"></i>
                                Adding client to: <strong>{{ $department->department }}</strong>
                            </div>
                        @endif

                        <div class="row g-3">

                            {{-- Client Name --}}
                            <div class="col-md-6">
                                <label for="quick_client_name" class="form-label">
                                    Client Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" wire:model.blur="client_name"
                                    class="form-control @error('client_name') is-invalid @enderror"
                                    id="quick_client_name" placeholder="e.g., John Doe" maxlength="100" autofocus>
                                @error('client_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Company Name --}}
                            <div class="col-md-6">
                                <label for="quick_company_name" class="form-label">
                                    Company Name
                                </label>
                                <input type="text" wire:model.blur="company_name"
                                    class="form-control @error('company_name') is-invalid @enderror"
                                    id="quick_company_name" placeholder="e.g., Acme Corporation" maxlength="100">
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label for="quick_phone" class="form-label">
                                    Phone Number
                                </label>
                                <input type="tel" wire:model.blur="phone"
                                    class="form-control @error('phone') is-invalid @enderror" id="quick_phone"
                                    placeholder="e.g., +252 61 234 5678" maxlength="20">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="quick_email" class="form-label">
                                    Email Address
                                </label>
                                <input type="email" wire:model.blur="email"
                                    class="form-control @error('email') is-invalid @enderror" id="quick_email"
                                    placeholder="e.g., client@example.com" maxlength="100">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div class="col-12">
                                <label for="quick_address" class="form-label">
                                    Address
                                </label>
                                <textarea wire:model.blur="address" class="form-control @error('address') is-invalid @enderror" id="quick_address"
                                    rows="3" placeholder="Enter full address..." maxlength="255"></textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Active Status --}}
                            <div class="col-12">
                                <div class="form-check">
                                    <input wire:model="is_active" type="checkbox" class="form-check-input"
                                        id="quick_is_active">
                                    <label class="form-check-label" for="quick_is_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" wire:click="closeModal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">
                                <i class="mdi mdi-check me-1"></i> Save Client
                            </span>
                            <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Backdrop --}}
    <div class="modal-backdrop fade show"></div>

</div>

{{-- Scripts --}}
@push('scripts')
    <script>
        // Close modal on event
        Livewire.on('close-quick-add-client', () => {
            // Add any cleanup if needed
        });
    </script>
@endpush
