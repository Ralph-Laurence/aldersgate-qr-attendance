<div class="master-header">
    <div class="p-2 primary-bg-color text-light text-center font-condensed">
        {{ 'Library Attendance Monitoring System (LAMS)' }}
    </div>
    <header class="navbar navbar-expand-md bg-white d-print-none shadow-none border-bottom">
        <div class="container-xl">
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>
            <h1 class="navbar-brand d-none-navbar-horizontal mb-0 pe-0 pe-md-3">
                <a href="{{ route('qr-scan') }}">
                    <img src="{{ asset('img/aldersgate.svg') }}" width="32" height="32" alt="logo"
                        class="navbar-brand-image d-inline-block">
                    <span class="align-middle font-condensed text-primary-color">{{ 'Aldersgate College Inc.' }}</span>
                </a>
            </h1>
            <div class="navbar-nav flex-row order-md-last">

                <div class="nav-item d-none d-md-flex me-3">
                    <button type="button" class="btn btn-link px-3 me-2 navbar-item-link-button">
                        {{ 'About' }}
                    </button>
                    <button type="button" class="btn btn-link px-3 me-2 navbar-item-link-button">
                        {{ 'Support' }}
                    </button>
                    <div class="btn-list">
                        <a href="#" class="btn flat-btn flat-btn-primary">
                            {{ 'Generate QR' }}
                        </a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <ul class="navbar-nav">
                        <!-- Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle navbar-item-link-button" href="#"
                                id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown"
                                aria-expanded="false">
                                {{ 'Options' }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li>
                                    <a class="dropdown-item" href="#">{{ 'Admin Panel' }}</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Another action</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

</div>
