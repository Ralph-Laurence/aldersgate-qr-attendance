@extends('layouts.backoffice.master')

@section('title')
{{ "Attendance" }}
@endsection

@push('styles')
    <style>
        .folder-tile 
        {
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            user-select: none;
            height: 190px;
            max-height: 190px;
            width: 165px;
            max-width: 165px;
            transition: background-color .25s ease-in-out;
        }

        .folder-tile h6 
        {
            color: var(--text-color-500);
            transition: color .25s ease-in-out;
        }

        .folder-tile:hover h6 {
            color: var(--text-color-800);
        }

        .folder-tile:hover {
            background-color: var(--flat-color-light);
        }

        .folder-tile img 
        {
            transition: width .25s ease-in-out, 
                        height .25s ease-in-out;
        }

        .folder-tile:hover img {
            width: 120px;
            height: 120px;
        }
    </style>
@endpush

@section('content')

@once
@include('modals.message-box')
@include('modals.toast')
@endonce

<div class="content-wrapper py-3">

    @include('layouts.backoffice.sidebar')

    <main class="main-content overflow-y-auto ps-2 no-native-scroll" data-simplebar>

        @include('layouts.backoffice.header')

        <div class="container-fluid p-4">

            <div class="row">
                <div class="col">
                    <div class="card mb-4 table-card pb-4">
                        <div class="card-header pb-0">
                            <p class="card-title mb-2 fw-normal">{{ "Browse attendance" }}</p>
                        </div>
                        <div class="card-body py-2 px-4">
                           <div class="d-flex flex-row gap-4">
                                
                                <div class="folder-tile flex-column rounded-3 px-3 py-2" data-href="{{ $props['elem']['link'] }}">
                                    <img src="{{ asset('img/folder_closed.png') }}" data-img-initial="{{ asset('img/folder_closed.png') }}" data-img-hovered="{{ asset('img/folder_opened.png') }}" alt="folder" width="80" height="80">
                                    <h6 class="mb-1">{{ 'Elementary' }}</h6>
                                    <small class="opacity-75">{{ $props['elem']['count'] . " Records" }}</small>
                                </div>

                                <div class="folder-tile flex-column rounded-3 px-3 py-2" data-href="{{ $props['juniors']['link'] }}">
                                    <img src="{{ asset('img/folder_closed.png') }}" data-img-initial="{{ asset('img/folder_closed.png') }}" data-img-hovered="{{ asset('img/folder_opened.png') }}" alt="folder" width="80" height="80">
                                    <h6 class="mb-1">{{ 'Junior High' }}</h6>
                                    <small class="opacity-75">{{ $props['juniors']['count'] . " Records" }}</small>
                                </div>

                                <div class="folder-tile flex-column rounded-3 px-3 py-2" data-href="{{ $props['seniors']['link'] }}">
                                    <img src="{{ asset('img/folder_closed.png') }}" data-img-initial="{{ asset('img/folder_closed.png') }}" data-img-hovered="{{ asset('img/folder_opened.png') }}" alt="folder" width="80" height="80">
                                    <h6 class="mb-1">{{ 'Senior High' }}</h6>
                                    <small class="opacity-75">{{ $props['seniors']['count'] . " Records" }}</small>
                                </div>

                                <div class="folder-tile flex-column rounded-3 px-3 py-2" data-href="{{ $props['college']['link'] }}">
                                    <img src="{{ asset('img/folder_closed.png') }}" data-img-initial="{{ asset('img/folder_closed.png') }}" data-img-hovered="{{ asset('img/folder_opened.png') }}" alt="folder" width="80" height="80">
                                    <h6 class="mb-1">{{ 'College' }}</h6>
                                    <small class="opacity-75">{{ $props['college']['count'] . " Records" }}</small>
                                </div>

                           </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('layouts.backoffice.footer')

        </div>
    </main>
</div>

@endsection

@push('scripts')
    <script>
        $(() => 
        {
            $('.folder-tile')
            .on('click', function()
            {
                var href = $(this).data('href');
                window.location = href;
            })
            .on('mouseenter', function() 
            {
                var $img = $(this).find('img');

                $img.attr('src', $img.data('img-hovered'));
            })
            .on('mouseleave', function() 
            {
                var $img = $(this).find('img');

                $img.attr('src', $img.data('img-initial'));
            });
        });
    </script>
@endpush