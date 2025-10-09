{{--
    resources/views/livewire/masters/user/add-user.blade.php

    Add/Edit User Modal with Profile Photo at Top
--}}

<div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            {{-- Modal Header --}}
            <div class="modal-header">
                <h5 class="modal-title">{{ $editMode ? 'Edit' : 'Add New' }} User</h5>
                <button type="button" class="btn-close" wire:click="closeModal"></button>
            </div>

            {{-- Modal Body with Form --}}
            <form wire:submit.prevent="save">
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    
                    {{-- Profile Photo Section (Top) --}}
                    <div class="mb-4 text-center">
                        <label class="form-label d-block fw-bold">Profile Photo</label>
                        
                        {{-- Current/Preview Photo --}}
                        <div class="mb-3">
                            @if($profile_photo_path)
                                {{-- New upload preview --}}
                                @if(is_object($profile_photo_path))
                                    <img src="{{ $profile_photo_path->temporaryUrl() }}" 
                                         alt="Preview"
                                         class="border rounded-circle"
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <div class="border rounded-circle d-inline-flex align-items-center justify-content-center bg-light" 
                                         style="width: 120px; height: 120px;">
                                        <i class="ti ti-user text-muted" style="font-size: 48px;"></i>
                                    </div>
                                @endif
                            @elseif($editMode && $existing_photo)
                                {{-- Existing photo in edit mode --}}
                                <img src="{{ asset('storage/' . $existing_photo) }}" 
                                     alt="Current Photo"
                                     class="border rounded-circle"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                {{-- No photo placeholder --}}
                                <div class="text-white border rounded-circle d-inline-flex align-items-center justify-content-center bg-primary" 
                                     style="width: 120px; height: 120px;">
                                    <i class="ti ti-user" style="font-size: 48px;"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Upload Progress --}}
                        <div wire:loading wire:target="profile_photo_path" class="mb-2">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span class="small text-primary">Uploading...</span>
                        </div>

                        {{-- File Input --}}
                        <div class="d-inline-block">
                            <input wire:model="profile_photo_path" 
                                   type="file" 
                                   class="form-control form-control-sm @error('profile_photo_path') is-invalid @enderror" 
                                   accept="image/*"
                                   id="profile_photo_path"
                                   onchange="validateFileSize(this)">
                            <div id="fileSizeError" class="invalid-feedback" style="display: none;">
                                File size exceeds 2MB. Please choose a smaller file.
                            </div>
                            @error('profile_photo_path')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="mt-1 form-text text-muted d-block">
                                Max size: 2MB. Supported: JPG, PNG, GIF
                            </small>
                        </div>
                    </div>

                    <hr class="mb-4">

                    {{-- Form Fields --}}
                    <div class="row g-3">

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input wire:model="name" type="text"
                                class="form-control @error('name') is-invalid @enderror" id="name"
                                placeholder="e.g. John Doe" maxlength="255">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <input wire:model="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" id="email"
                                placeholder="e.g. john@example.com" maxlength="255">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        @if(!$editMode)
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <input wire:model="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password"
                                    placeholder="Enter password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">
                                    Confirm Password <span class="text-danger">*</span>
                                </label>
                                <input wire:model="password_confirmation" type="password"
                                    class="form-control" id="password_confirmation"
                                    placeholder="Confirm password">
                            </div>
                        @else
                            <div class="col-md-12">
                                <div class="mb-0 alert alert-info small">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Leave password fields empty to keep current password
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password</label>
                                <input wire:model="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password"
                                    placeholder="Enter new password (optional)">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input wire:model="password_confirmation" type="password"
                                    class="form-control" id="password_confirmation"
                                    placeholder="Confirm new password">
                            </div>
                        @endif

                        {{-- Role --}}
                        <div class="col-md-6">
                            <label for="role_id" class="form-label">
                                Role <span class="text-danger">*</span>
                            </label>
                            <select wire:model="role_id"
                                class="form-select @error('role_id') is-invalid @enderror" id="role_id">
                                <option value="">-- Select Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input wire:model="phone" type="text"
                                class="form-control @error('phone') is-invalid @enderror" id="phone"
                                placeholder="e.g. +252-XXX-XXXX" maxlength="20">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Departments --}}
                        <div class="mt-3 col-md-12">
                            <label for="selectedDepartments" class="form-label">
                                Departments <span class="text-danger">*</span>
                            </label>
                            <div class="p-2 mb-2 border alert alert-light">
                                <i class="ti ti-info-circle me-1"></i>
                                <small class="text-muted">
                                    Select the departments this user will have access to
                                </small>
                            </div>
                            <select wire:model="selectedDepartments" 
                                    class="form-select @error('selectedDepartments') is-invalid @enderror" 
                                    id="selectedDepartments"
                                    multiple 
                                    size="5">
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->department }}</option>
                                @endforeach
                            </select>
                            @error('selectedDepartments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="ti ti-hand-click me-1"></i>
                                Hold <kbd>Ctrl</kbd> (Windows) or <kbd>Cmd</kbd> (Mac) to select multiple
                            </small>
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
@push('scripts')
<script>
function validateFileSize(input) {
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
    const errorDiv = document.getElementById('fileSizeError');
    
    if (input.files && input.files[0]) {
        const fileSize = input.files[0].size;
        
        if (fileSize > maxSize) {
            errorDiv.style.display = 'block';
            input.classList.add('is-invalid');
            input.value = '';
            return false;
        } else {
            errorDiv.style.display = 'none';
            input.classList.remove('is-invalid');
            return true;
        }
    }
}
</script>
@endpush
