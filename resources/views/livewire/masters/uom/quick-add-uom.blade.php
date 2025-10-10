{{-- resources/views/livewire/masters/uoms/quick-add-uom.blade.php --}}

<div>
    {{-- Modal --}}
    <div class="modal fade show"
         style="display: block;"
         tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                {{-- Modal Header --}}
                <div class="text-white modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="mdi mdi-package-variant-closed me-2"></i>
                        Quick Add UOM
                    </h5>
                    <button type="button"
                            class="btn-close btn-close-white"
                            wire:click="closeModal"></button>
                </div>

                {{-- Modal Body --}}
                <form wire:submit.prevent="save">
                    <div class="modal-body">

                        <div class="row g-3">

                            {{-- UOM Code --}}
                            <div class="col-12">
                                <label for="quick_uom_code" class="form-label">
                                    Code <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       wire:model="code"
                                       class="form-control text-uppercase @error('code') is-invalid @enderror"
                                       id="quick_uom_code"
                                       placeholder="e.g., PCS, KG, LTR"
                                       maxlength="10"
                                       autofocus>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Short code for the unit (max 10 characters)</small>
                            </div>

                            {{-- UOM Name --}}
                            <div class="col-12">
                                <label for="quick_uom_name" class="form-label">
                                    Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       wire:model="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="quick_uom_name"
                                       placeholder="e.g., Pieces, Kilogram, Liter"
                                       maxlength="100">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Full name of the unit of measurement</small>
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label for="quick_uom_description" class="form-label">
                                    Description
                                </label>
                                <textarea
                                    wire:model="description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    id="quick_uom_description"
                                    rows="3"
                                    placeholder="Enter additional details about this UOM..."
                                    maxlength="255"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Active Status --}}
                            <div class="col-12">
                                <div class="form-check">
                                    <input wire:model="is_active"
                                           type="checkbox"
                                           class="form-check-input"
                                           id="quick_is_active_uom">
                                    <label class="form-check-label" for="quick_is_active_uom">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-light"
                                wire:click="closeModal">
                            Cancel
                        </button>
                        <button type="submit"
                                class="btn btn-primary"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">
                                <i class="mdi mdi-check me-1"></i> Save UOM
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
    <div class="modal-backdrop fade show"
         style="background: rgba(0,0,0,0.5);"
         wire:click="closeModal"></div>
</div>
