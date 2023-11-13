@once
@push('styles')
<style>
    .flat-worksheet-tabs {
        box-shadow: rgba(0, 0, 0, 0.05) 0px 20px 27px 0px;
        height: 36px;
        max-height: 36px;
    }

    .flat-worksheet-tabs .leading-label,
    .flat-worksheet-tabs .trailing-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-color-400);
    }


    .flat-worksheet-tabs .flat-worksheet-tabs-item 
    {
        background-color: var(--flat-control-bg);
        padding: 2px 12px;
        border-radius: 2rem !important;
        font-weight: 600;
        color: var(--text-color-700);
        box-shadow: none !important;
        font-size: 0.8125rem;
        display: flex;
        align-items: center;
        width: fit-content;
        white-space: nowrap;
    }

    .flat-worksheet-tabs .flat-worksheet-tabs-item:hover,
    .flat-worksheet-tabs .flat-worksheet-tabs-item:active {
        background-color: #DBDBDE;
        color: var(--text-color-800);
    }

    .flat-worksheet-tabs .flat-worksheet-tabs-item.active {
        background-color: var(--flat-color-primary);
        color: white;
    }

    .flat-worksheet-tabs .flat-worksheet-tabs-item.active::after {
        font-family: var(--fas-font);
        content: '\f00c';
        padding-left: 6px;
    }
</style>
@endpush
@endonce
<div {{ $attributes->merge(['class' => "flat-controls flat-worksheet-tabs d-inline-flex align-items-center gap-2 bg-white rounded-8 px-3 py-1 text-sm"]) }} role="group">
    
    @if ($attributes->has('leading-label'))
        <span class="leading-label">{{ $attributes->get('leading-label') }}</span>    
    @endif
    
    <div class="d-flex align-items-center gap-2">
        {{ $navItems }}
    </div>

    @if ($attributes->has('trailing-label'))
        <span class="trailing-label">{{ $attributes->get('trailing-label') }}</span>    
    @endif
    
</div>
