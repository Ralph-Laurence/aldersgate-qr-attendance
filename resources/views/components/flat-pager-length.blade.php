@once
@push('styles')
<style>
    .flat-pager {
        box-shadow: rgba(0, 0, 0, 0.05) 0px 20px 27px 0px;
        height: 36px;
        max-height: 36px;
    }

    .flat-pager .pager-label {
        font-size: 0.8125rem;
        font-weight: 600;
    }


    .flat-pager .btn-page-length {
        background-color: var(--flat-control-bg);
        padding: 2px 8px;
        border-radius: 2rem !important;
        font-weight: 600;
        color: var(--text-color-700);
        box-shadow: none !important;
        font-size: 0.8125rem;
    }

    .flat-pager .btn-page-length::after {
        font-family: var(--fas-font);
        content: '\f078';
        padding-left: 6px;
    }

    .flat-pager .btn-page-length:hover,
    .flat-pager .btn-page-length:active {
        background-color: #DBDBDE;
        color: var(--text-color-800);
    }

    .flat-pager .pagination-length-control {
        color: var(--text-color-500);
    }

    .flat-pager .page-length-item {
        color: var(--text-color-500);
        display: inline-block !important;
    }

    .flat-pager .page-length-item:hover {
        color: var(--text-color-700);
        font-weight: 600;
    }

    .flat-pager .page-length-item.selected {
        background-color: #F4F5FB;
        color: var(--text-color-700);
        font-weight: 600;
    }

    .flat-pager .page-length-item.selected::after {
        font-family: var(--fas-font);
        content: '\f00c';
        position: absolute;
        right: 12px;
    }
</style>
@endpush
@endonce
<div {{ $attributes->merge(['class' => "flat-pager d-inline-flex align-items-center gap-2 bg-white rounded-8 px-3 py-1
    text-sm"]) }}>
    <span class="pager-label">{{ "Show" }}</span>
    <div class="dropdown z-100">
        <button class="btn btn-page-length" data-mdb-toggle="dropdown" id="pageLengthMenuButton"></button>
        <ul class="dropdown-menu user-select-none" aria-labelledby="pageLengthMenuButton">
            {{-- Append items here --}}
        </ul>
    </div>
    <span class="pager-label">{{ "entries" }}</span>
</div>