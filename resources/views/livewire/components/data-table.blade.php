{{-- resources/views/livewire/components/data-table.blade.php --}}
{{-- Generic Data Table Component with uBold ecommerce-customers styling --}}

<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- Header Section with Search and Actions --}}
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="javascript:void(0);"
                               wire:click="$dispatch('openModal')"
                               class="btn btn-danger mb-2">
                                <i class="mdi mdi-plus-circle me-2"></i> Add {{ $entityName ?? 'New' }}
                            </a>
                        </div>
                        <div class="col-sm-8">
                            <div class="text-sm-end">
                                {{-- Bulk Actions Dropdown --}}
                                @if(count($selectedItems) > 0)
                                <div class="btn-group mb-2 me-1">
                                    <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Bulk Action <i class="mdi mdi-chevron-down"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);" wire:click="exportSelected">
                                            <i class="mdi mdi-export me-1"></i> Export
                                        </a>
                                        <a class="dropdown-item" href="javascript:void(0);" wire:click="deleteSelected" onclick="return confirm('Delete selected items?')">
                                            <i class="mdi mdi-delete me-1"></i> Delete
                                        </a>
                                    </div>
                                </div>
                                @endif

                                {{-- Per Page Selector --}}
                                <div class="btn-group mb-2 me-1">
                                    <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Show {{ $perPage }} <i class="mdi mdi-chevron-down"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);" wire:click="$set('perPage', 10)">10</a>
                                        <a class="dropdown-item" href="javascript:void(0);" wire:click="$set('perPage', 25)">25</a>
                                        <a class="dropdown-item" href="javascript:void(0);" wire:click="$set('perPage', 50)">50</a>
                                        <a class="dropdown-item" href="javascript:void(0);" wire:click="$set('perPage', 100)">100</a>
                                    </div>
                                </div>

                                {{-- Export Button --}}
                                <button type="button" class="btn btn-light mb-2">
                                    <i class="mdi mdi-export"></i>
                                </button>
                            </div>
                        </div><!-- end col-->
                    </div>

                    {{-- Search Bar --}}
                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length">
                                <label class="form-label">
                                    Selected: <span class="badge bg-primary">{{ count($selectedItems) }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_filter text-md-end">
                                <label class="form-label">
                                    <input type="search"
                                           class="form-control form-control-sm"
                                           placeholder="Search..."
                                           wire:model.live.debounce.300ms="search">
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Loading Indicator --}}
                    <div wire:loading class="text-center mb-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    {{-- Select All Checkbox --}}
                                    <th style="width: 20px;">
                                        <div class="form-check">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="customCheck1"
                                                   wire:model.live="selectAll">
                                            <label class="form-check-label" for="customCheck1">&nbsp;</label>
                                        </div>
                                    </th>

                                    {{-- Dynamic Table Headers - Override in child component --}}
                                    {{ $tableHeaders ?? '' }}

                                    {{-- Actions Column --}}
                                    <th style="width: 125px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Dynamic Table Body - Override in child component --}}
                                {{ $tableBody ?? '' }}

                                {{-- No Data Found --}}
                                @if($data->isEmpty())
                                <tr>
                                    <td colspan="100" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="mdi mdi-information-outline" style="font-size: 2rem;"></i>
                                            <p class="mt-2">No records found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="row mt-3">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info">
                                Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} of {{ $data->total() }} entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers float-end">
                                {{ $data->links() }}
                            </div>
                        </div>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
</div>
