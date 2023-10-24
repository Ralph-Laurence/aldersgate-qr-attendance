<div {{ $attributes->merge(['class' => "d-inline-flex gap-2 bg-white rounded-8 px-3 py-2 text-sm"]) }}>
    {{ "Show" }}
    <div class="dropdown z-100">
        <button class="btn btn-page-length" data-mdb-toggle="dropdown"
            id="pageLengthMenuButton"></button>
        <ul class="dropdown-menu user-select-none" aria-labelledby="pageLengthMenuButton">
            {{-- Append items here --}}
        </ul>
    </div>
    {{ "entries" }}
</div>
