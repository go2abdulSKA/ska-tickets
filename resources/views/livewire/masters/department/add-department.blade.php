{{--
    resources/views/livewire/masters/department/add-department.blade.php

    Add/Edit Department Modal with Predefined Logos
--}}

<div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            {{-- Modal Header --}}
            <div class="modal-header">
                <h5 class="modal-title">{{ $editMode ? 'Edit' : 'Add New' }} Department</h5>
                <button type="button" class="btn-close" wire:click="closeModal"></button>
            </div>

            {{-- Modal Body with Form --}}
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Department Name --}}
                        <div class="col-md-6">
                            <label for="department" class="form-label">
                                Department Name <span class="text-danger">*</span>
                            </label>
                            <input wire:model="department" type="text"
                                class="form-control @error('department') is-invalid @enderror" id="department"
                                placeholder="e.g. Camp & Accommodation" maxlength="100">
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Short Name --}}
                        <div class="col-md-6">
                            <label for="short_name" class="form-label">
                                Short Name
                            </label>
                            <input wire:model="short_name" type="text"
                                class="form-control @error('short_name') is-invalid @enderror" id="short_name"
                                placeholder="e.g. C&A" maxlength="50">
                            @error('short_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Optional shortened version
                            </small>
                        </div>

                        {{-- Prefix --}}
                        <div class="col-md-6">
                            <label for="prefix" class="form-label">
                                Prefix <span class="text-danger">*</span>
                            </label>
                            <input wire:model="prefix" type="text"
                                class="form-control @error('prefix') is-invalid @enderror" id="prefix"
                                placeholder="e.g. C/A" maxlength="10">
                            @error('prefix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Used in ticket numbers
                            </small>
                        </div>

                        {{-- Form Name --}}
                        <div class="col-md-6">
                            <label for="form_name" class="form-label">
                                Form Name
                            </label>
                            <input wire:model="form_name" type="text"
                                class="form-control @error('form_name') is-invalid @enderror" id="form_name"
                                placeholder="e.g. Camp & Accommodation Finance Ticket" maxlength="100">
                            @error('form_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Custom name for forms/invoices
                            </small>
                        </div>

                        {{-- Logo Selection Section --}}
                        <div class="col-md-12">
                            <label class="form-label">Department Logo</label>
                            
                            {{-- Show existing logo if in edit mode --}}
                            @if($editMode && $existing_logo)
                                <div class="p-3 mb-3 border rounded bg-light">
                                    <p class="mb-2 fw-bold small">Current Logo:</p>
                                    <img src="{{ asset('storage/' . $existing_logo) }}" 
                                         alt="Current Logo" 
                                         class="border rounded"
                                         style="max-height: 80px; max-width: 200px; object-fit: contain;">
                                </div>
                            @endif

                            {{-- Predefined Logo Dropdown --}}
                            <div class="mb-3">
                                <label for="selectedPredefinedLogo" class="form-label small">
                                    Choose from Predefined Logos:
                                </label>
                                <select wire:model.live="selectedPredefinedLogo" 
                                        class="form-select @error('selectedPredefinedLogo') is-invalid @enderror" 
                                        id="selectedPredefinedLogo">
                                    <option value="">-- Select a Logo --</option>
                                    
                                    {{-- List predefined logos --}}
                                    @foreach($predefinedLogos as $logo)
                                        <option value="{{ $logo['path'] }}">{{ $logo['name'] }}</option>
                                    @endforeach
                                    
                                    {{-- Option to add new logo --}}
                                    <option value="custom">➕ Add New Logo to Library</option>
                                </select>
                                @error('selectedPredefinedLogo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Preview Selected Predefined Logo --}}
                            @if($selectedPredefinedLogo && $selectedPredefinedLogo !== 'custom')
                                <div class="p-3 mb-3 border rounded bg-light">
                                    <p class="mb-2 fw-bold small">Selected Logo Preview:</p>
                                    <img src="{{ asset('storage/' . $selectedPredefinedLogo) }}" 
                                         alt="Selected Logo" 
                                         class="border rounded"
                                         style="max-height: 100px; max-width: 250px; object-fit: contain;">
                                </div>
                            @endif

                            {{-- Custom Upload Section (shows when "Add New Logo" is selected) --}}
                            @if($showCustomUpload)
                                <div class="p-3 border rounded bg-light">
                                    <p class="mb-2 fw-bold small text-primary">
                                        <i class="ti ti-upload me-1"></i>
                                        Upload New Logo (Will be saved for future use)
                                    </p>
                                    
                                    <input wire:model="logo" type="file" 
                                        class="form-control @error('logo') is-invalid @enderror" 
                                        id="logo"
                                        accept="image/*"
                                        onchange="validateFileSize(this)">
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    {{-- File size error message --}}
                                    <div id="fileSizeError" class="invalid-feedback" style="display: none;">
                                        File size exceeds 2MB. Please choose a smaller file.
                                    </div>
                                    
                                    {{-- Upload Progress Indicator --}}
                                    <div wire:loading wire:target="logo" class="mt-2">
                                        <div class="p-2 text-center bg-white border rounded">
                                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <span class="small text-primary">Uploading logo...</span>
                                        </div>
                                    </div>
                                    
                                    <small class="form-text text-muted">
                                        Max size: 2MB. This logo will be saved to the predefined library for future use.
                                    </small>

                                    {{-- Preview new upload --}}
                                    @if ($logo)
                                        <div class="mt-3" wire:loading.remove wire:target="logo">
                                            <p class="mb-2 fw-bold small">New Logo Preview:</p>
                                            @if(is_object($logo) && method_exists($logo, 'temporaryUrl'))
                                                @try
                                                    <img src="{{ $logo->temporaryUrl() }}" 
                                                         alt="New Logo Preview" 
                                                         class="border rounded"
                                                         style="max-height: 100px; max-width: 250px; object-fit: contain;">
                                                @catch(\Exception $e)
                                                    <div class="p-3 text-center bg-white border rounded">
                                                        <i class="ti ti-photo text-muted" style="font-size: 48px;"></i>
                                                        <p class="mt-2 mb-0 text-muted small">
                                                            Logo uploaded successfully<br>
                                                            Preview will show after saving
                                                        </p>
                                                    </div>
                                                @endtry
                                            @else
                                                <div class="p-3 text-center bg-white border rounded">
                                                    <i class="ti ti-photo text-muted" style="font-size: 48px;"></i>
                                                    <p class="mt-2 mb-0 text-muted small">
                                                        Logo uploaded successfully<br>
                                                        Preview will show after saving
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <small class="mt-2 form-text text-muted d-block">
                                <i class="ti ti-info-circle me-1"></i>
                                Select from existing logos or add a new one to the library
                            </small>
                        </div>

                        {{-- Notes --}}
                        <div class="col-md-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea wire:model="notes" 
                                class="form-control @error('notes') is-invalid @enderror" 
                                id="notes"
                                rows="3" 
                                placeholder="Optional notes about this department" 
                                maxlength="500"></textarea>
                            @error('notes')
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

{{-- File Size Validation Script --}}
<script>
function validateFileSize(input) {
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
    const errorDiv = document.getElementById('fileSizeError');
    
    if (input.files && input.files[0]) {
        const fileSize = input.files[0].size;
        
        if (fileSize > maxSize) {
            // Show error
            errorDiv.style.display = 'block';
            input.classList.add('is-invalid');
            
            // Clear the file input
            input.value = '';
            
            // Show toast notification if available
            if (typeof window.dispatchEvent === 'function') {
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        type: 'error',
                        message: 'File size exceeds 2MB. Please choose a smaller file.'
                    }
                }));
            }
            
            return false;
        } else {
            // Hide error
            errorDiv.style.display = 'none';
            input.classList.remove('is-invalid');
            return true;
        }
    }
}
</script>
