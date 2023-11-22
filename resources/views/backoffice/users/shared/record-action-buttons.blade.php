@php
    $userStatusAction = '';

    if (!empty($statusAction))
        $userStatusAction = $statusAction;

@endphp
@push('styles')
    <style>
    .dropdown .dropdown-menu .dropdown-item {
        color: var(--text-color-500);
        display: flex;
        align-items: center;
    }

    .dropdown .dropdown-menu .dropdown-item:hover {
        color: var(--text-color-800);
    }
    .dropdown .dropdown-menu .dropdown-item .menu-icon {
        width: 24px !important;
        height: 24px !important;
    }
    </style>
@endpush
<div class="dropdown">
    <button class="btn btn-sm px-2 btn-record-options" data-mdb-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-gear"></i>
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" role="button">
                <span class="menu-icon flex-center">
                    <i class="fas fa-info-circle text-primary"></i>
                </span>
                <span class="menu-text ms-1">{{ 'Details' }}</span>
            </a>
        </li>
        <li>
            @if ($userStatusAction == 'disable')
                <a class="dropdown-item option-disable-user" role="button">
                    <span class="menu-icon flex-center">
                        <i class="fas fa-close text-danger"></i>
                    </span>
                    <span class="menu-text ms-1">{{ 'Disable' }}</span>
                </a>
            @elseif ($userStatusAction == 'enable')
                <a class="dropdown-item option-enable-user" role="button">
                    <span class="menu-icon flex-center">
                        <i class="fas fa-circle-check text-flat-primary-dark"></i>
                    </span>
                    <span class="menu-text ms-1">{{ 'Enable' }}</span>
                </a>
            @endif
        </li>
        {{-- <li><a class="dropdown-item" role="button">Something else here</a></li> --}}
    </ul>
</div>

<button class="btn btn-sm px-2 btn-edit">
    <i class="fa-solid fa-pen"></i>
</button>
<button class="btn btn-sm px-2 btn-delete">
    <i class="fa-solid fa-trash"></i>
</button>