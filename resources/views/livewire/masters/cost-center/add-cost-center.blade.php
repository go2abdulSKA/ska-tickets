{{--
    resources/views/livewire/masters/cost-center/add-cost-center.blade.php

    Add/Edit Cost Center Modal
    Bootstrap modal for creating or updating cost centers
--}}

<div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            {{-- Modal Header --}}
            <div class="modal-header">
                <h5 class="modal-title">{{ $editMode ? 'Edit' : 'Add New' }} Cost Center</h5>
                <button type="button" class="btn-close" wire:click="closeModal"></button>
            </div>

            {{-- Modal Body with Form --}}
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Code Field --}}
                        <div class="col-md-6">
                            <label for="code" class="form-label">
                                Code <span class="text-danger">*</span>
                            </label>
                            <input wire:model="code" type="text"
                                class="form-control @error('code') is-invalid @enderror" id="code"
                                placeholder="e.g. ADMIN, IT, HR" maxlength="20">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Common: ADMIN, IT, HR, FIN, OPS
                            </small>
                        </div>

                        {{-- Name Field --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                Name <span class="text-danger">*</span>
                            </label>
                            <input wire:model="name" type="text"
                                class="form-control @error('name') is-invalid @enderror" id="name"
                                placeholder="e.g. Administration" maxlength="100">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description Field --}}
                        <div class="col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" id="description"
                                rows="3" placeholder="Optional description" maxlength="500"></textarea>
                            @error('description')
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
