{{-- resources/views/coming-soon.blade.php --}}
<x-app-layout>

    <x-ui.page-header :$title page="Coming-Soon" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="py-5 text-center card-body">
                    <div class="mb-4">
                        <i class="mdi mdi-wrench-clock" style="font-size: 5rem; color: #6c757d;"></i>
                    </div>
                    <h2 class="mb-3">{{ $title }}</h2>
                    <p class="mb-4 text-muted">This page is currently under development and will be available soon.
                    </p>

                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="mdi mdi-home me-1"></i> Back to Dashboard
                        </a>
                    </div>

                    <div class="mx-auto mt-4 alert alert-info" style="max-width: 600px;">
                        <strong>Note:</strong> You've successfully logged in and the system is working. We're
                        building this page next!
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
