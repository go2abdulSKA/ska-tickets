@props(['title', 'page' => '', 'subpage' => ''])

<div class="page-title-head d-flex align-items-center">
    <div class="flex-grow-1">
        <h4 class="m-0 fs-xl fw-bold">{{ $title }}</h4>
    </div>

    <div class="text-end">
        <ol class="py-0 m-0 breadcrumb">
            <li class="breadcrumb-item">
                <a href="javascript: void(0);">{{ config('app.name') }}</a>
            </li>

            {{-- <li class="breadcrumb-item">
                <a href="javascript: void(0);">Pages</a>
            </li> --}}

            <li class="breadcrumb-item active">{{ $page }}</li>
        </ol>
    </div>
</div>
