{{--
    resources/views/livewire/masters/client/add-client.blade.php

    Add/Edit Client Modal
--}}

<div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            {{-- Modal Header --}}
            <div class="modal-header">
                <h5 class="modal-title">{{ $editMode ? 'Edit' : 'Add New' }} Client</h5>
                <button type="button" class="btn-close" wire:click="closeModal"></button>
            </div>

            {{-- Modal Body with Form --}}
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Department --}}
                        <div class="col-md-6">
                            <label for="department_id" class="form-label">
                                Department <span class="text-danger">*</span>
                            </label>
                            <select wire:model="department_id"
                                class="form-select @error('department_id') is-invalid @enderror" id="department_id">
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->department }}</option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Client Name --}}
                        <div class="col-md-6">
                            <label for="client_name" class="form-label">
                                Client Name <span class="text-danger">*</span>
                            </label>
                            <input wire:model="client_name" type="text"
                                class="form-control @error('client_name') is-invalid @enderror" id="client_name"
                                placeholder="e.g. John Doe" maxlength="100">
                            @error('client_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Company Name --}}
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">
                                Company Name
                            </label>
                            <input wire:model="company_name" type="text"
                                class="form-control @error('company_name') is-invalid @enderror" id="company_name"
                                placeholder="e.g. ABC Corporation" maxlength="100">
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label for="phone" class="form-label">
                                Phone Number
                            </label>
                            <input wire:model="phone" type="text"
                                class="form-control @error('phone') is-invalid @enderror" id="phone"
                                placeholder="e.g. +252-XXX-XXXX" maxlength="20">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-12">
                            <label for="email" class="form-label">
                                Email Address
                            </label>
                            <input wire:model="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" id="email"
                                placeholder="e.g. client@example.com" maxlength="100">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div class="col-md-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea wire:model="address" 
                                class="form-control @error('address') is-invalid @enderror" 
                                id="address"
                                rows="3" 
                                placeholder="Client address" 
                                maxlength="500"></textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status Checkbox --}}
                        <div class="col-md-12">
                            <div class="form-check">
                                <input wire:model="is_active" type="checkbox" class="form-check-input" id="is_active">
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Modal Footer with Action Buttons --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="closeModal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">
                            <i class="mdi mdi-content-save me-1"></i>
                            {{ $editMode ? 'Update' : 'Save' }}
                        </span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Saving...
                        </span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
