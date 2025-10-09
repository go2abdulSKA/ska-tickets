{{-- 
    resources/views/livewire/masters/user/view-user.blade.php
    
    View User Offcanvas - Profile Card Style
--}}

{{-- @php
    {{ asset('storage/' . $existing_photo) }}
@endphp --}}

{{-- Offcanvas --}}

<div class="offcanvas offcanvas-end show" style="visibility: visible; width: 400px;" tabindex="-1">
    {{-- Offcanvas Header --}}
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">User Profile</h5>
        <button type="button" class="btn-close" wire:click="closeOffcanvas"></button>
    </div>
    
    {{-- Offcanvas Body --}}
    <div class="offcanvas-body">

{{-- <p>{{ $viewUser->profile_photo_path }}</p> --}}
{{-- <p>{{ $existing_photo }}</p> --}}
{{-- <p>{{ $viewUser->profile_photo_url }}</p> --}}

        {{-- Profile Photo Section (Top Center) --}}
        <div class="mb-4 text-center">
            @if($viewUser->profile_photo_path)
                {{-- <img src="{{ $viewUser->profile_photo_url }}"  --}}
                <img src="{{ asset('storage/' . $viewUser->profile_photo_path) }}"
                     alt="{{ $viewUser->name }}"
                     class="border rounded-circle"
                     style="width: 120px; height: 120px; object-fit: cover;">
            @else
                <div class="text-white border rounded-circle d-inline-flex align-items-center justify-content-center bg-primary" 
                     style="width: 120px; height: 120px;">
                    <span style="font-size: 48px; font-weight: bold;">
                        {{ strtoupper(substr($viewUser->name, 0, 1)) }}
                    </span>
                </div>
            @endif
            
            {{-- Name (Large) --}}
            <h4 class="mt-3 mb-1">{{ $viewUser->name }}</h4>
            
            {{-- Email --}}
            <p class="mb-2 text-muted">
                <i class="ti ti-mail me-1"></i>
                <a href="mailto:{{ $viewUser->email }}">{{ $viewUser->email }}</a>
            </p>
            
            {{-- Status Badge --}}
            <span class="badge badge-soft-{{ $viewUser->is_active ? 'success' : 'danger' }}">
                {{ $viewUser->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        {{-- Role & Permissions --}}
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Role & Permissions</h6>

            {{-- Role --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Role:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-info font-14">
                        <i class="ti ti-shield me-1"></i>
                        {{ $viewUser->role->display_name ?? 'N/A' }}
                    </span>
                </p>
            </div>

            {{-- Departments --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Departments:</label>
                <div class="flex-wrap gap-1 mt-1 d-flex">
                    @forelse($viewUser->departments as $dept)
                        <span class="badge badge-soft-primary">
                            {{ $dept->short_name ?? $dept->department }}
                        </span>
                    @empty
                        <p class="mb-0 text-muted small">No departments assigned</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Contact Information</h6>

            {{-- Phone --}}
            @if($viewUser->phone)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Phone:</label>
                    <p class="mb-0">
                        <i class="ti ti-phone me-1"></i>
                        <a href="tel:{{ $viewUser->phone }}">{{ $viewUser->phone }}</a>
                    </p>
                </div>
            @else
                <p class="text-muted small">No phone number provided</p>
            @endif
        </div>

        {{-- Activity Statistics --}}
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Activity Statistics</h6>

            {{-- Tickets Count --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Tickets Created:</label>
                <p class="mb-0">
                    <span class="badge badge-soft-success font-14">
                        <i class="ti ti-file-text me-1"></i>
                        {{ $viewUser->tickets_count ?? 0 }} tickets
                    </span>
                </p>
            </div>

            {{-- 2FA Status --}}
            @if($viewUser->two_factor_secret)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Two Factor Auth:</label>
                    <p class="mb-0">
                        <span class="badge badge-soft-success">
                            <i class="ti ti-lock me-1"></i> Enabled
                        </span>
                    </p>
                </div>
            @endif
        </div>

        {{-- Audit Information --}}
        <div class="mb-4">
            <h6 class="p-2 mt-0 mb-3 text-uppercase bg-light">Audit Information</h6>

            {{-- Created By --}}
            <div class="mb-3">
                <label class="text-muted fw-bold">Created By:</label>
                <p class="mb-0">{{ $viewUser->creator->name ?? 'System' }}</p>
                <small class="text-muted">{{ $viewUser->created_at->format('d M, Y h:i A') }}</small>
            </div>

            {{-- Updated By --}}
            @if ($viewUser->updated_at != $viewUser->created_at)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Last Updated By:</label>
                    <p class="mb-0">{{ $viewUser->updater->name ?? 'System' }}</p>
                    <small class="text-muted">{{ $viewUser->updated_at->format('d M, Y h:i A') }}</small>
                </div>
            @endif

            {{-- Last Login --}}
            @if($viewUser->current_team_id)
                <div class="mb-3">
                    <label class="text-muted fw-bold">Last Activity:</label>
                    <p class="mb-0">
                        <span class="badge badge-soft-secondary">
                            {{ $viewUser->updated_at->diffForHumans() }}
                        </span>
                    </p>
                </div>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="gap-2 d-grid">
            {{-- Edit Button --}}
            @if($viewUser->id !== auth()->id())
                <button type="button" wire:click="edit({{ $viewUser->id }}); $set('showOffcanvas', false)"
                    class="btn btn-primary">
                    <i class="ti ti-edit me-1"></i> Edit User
                </button>
            @else
                <a href="{{ route('profile.show') }}" class="btn btn-primary">
                    <i class="ti ti-user-cog me-1"></i> Manage My Profile
                </a>
            @endif
            
            {{-- Close Button --}}
            <button type="button" wire:click="closeOffcanvas" class="btn btn-light">
                Close
            </button>
        </div>
    </div>
</div>

{{-- Backdrop --}}
<div class="offcanvas-backdrop fade show" wire:click="closeOffcanvas"></div>
