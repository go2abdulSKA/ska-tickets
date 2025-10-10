{{-- resources/views/livewire/masters/service-types/quick-add-service-type.blade.php --}}

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
                        <i class="mdi mdi-briefcase-plus me-2"></i>
                        Quick Add Service Type
                    </h5>
                    <button type="button"
                            class="btn-close btn-close-white"
                            wire:click="closeModal"></button>
                </div>

                {{-- Modal Body --}}
                <form wire:submit.prevent="save">
                    <div class="modal-body">

                        {{-- Department Info (Read-only) --}}
                        @if($department)
                            <div class="mb-3 alert alert-info">
                                <i class="mdi mdi-information-outline me-1"></i>
                                Adding service type to: <strong>{{ $department->department }}</strong>
                            </div>
                        @endif

                        <div class="row g-3">

                            {{-- Service Type Name --}}
                            <div class="col-12">
                                <label for="quick_service_type" class="form-label">
                                    Service Type Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       wire:model="service_type"
                                       class="form-control @error('service_type') is-invalid @enderror"
                                       id="quick_service_type"
                                       placeholder="e.g., Consulting Services"
                                       maxlength="100"
                                       autofocus>
                                @error('service_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Enter a descriptive name for this service type</small>
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label for="quick_description" class="form-label">
                                    Description
                                </label>
                                <textarea
                                    wire:model="description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    id="quick_description"
                                    rows="3"
                                    placeholder="Enter additional details about this service type..."
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
                                           id="quick_is_active_st">
                                    <label class="form-check-label" for="quick_is_active_st">
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
                                <i class="mdi mdi-check me-1"></i> Save Service Type
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
