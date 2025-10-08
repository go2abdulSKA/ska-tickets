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

            @isset($page)
                <li class="breadcrumb-item">
                    <a href="javascript: void(0);">{{ $page }}</a>
                </li>
            @endisset

            @isset($subpage)
                 <li class="breadcrumb-item active">
                    <a href="javascript: void(0);">{{ $subpage }}</a>
                </li>
            @endisset
        </ol>
    </div>
</div>
