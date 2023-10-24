@php

	$modalTitle = !empty($title) 	? $title 	: '';
	$modalId 	= !empty($id) 		? $id 		: '';
	$modalName	= !empty($name)		? $name		: '';

	$formAction = !empty($action)	? $action	: '';
	$formMethod	= !empty($method)	? $method	: '';

@endphp


@once
    @push('styles')
	<link rel="stylesheet" href="{{ asset('css/modals/modal-base.css') }}">
    @endpush    
@endonce

<div class="modal fade form-modal show" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}ModalLabel"
	data-mdb-backdrop="static" data-mdb-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h6 class="modal-title user-select-none" id="{{ $modalId }}ModalLabel">
					<i class="fas fa-pen p-2 rounded-circle modal-icon me-2"></i>
					<span class="modal-title-text form-modal-title">{{ $modalTitle }}</span>
				</h6>
				<button type="button" class="modal-close" data-mdb-dismiss="modal"
					aria-label="Close">
					<i class="fas fa-xmark"></i>
				</button>
			</div>
			<div class="modal-body border-0 shadow-0 modal-content pt-0">
				<form action="{{ $formAction }}" method="{{ $formMethod }}" class="modal-form" autocomplete="new-password">
                    @csrf
                    {{ $formInner }}

                </form>
			</div>
			<div class="modal-footer border-0 d-flex flex-row align-items-center">
				<x-flat-button as="btn-negative" theme="default" text="Cancel" data-mdb-dismiss="modal"/>
				<x-flat-button as="btn-positive" theme="primary" text="Save" data-mdb-dismiss="modal"/>
			</div>
		</div>
	</div>
</div>

@once

    @push('scripts')
    <script type="module">
    	import { ModalForm } from "{{ asset('js/modals/modal-form.js') }}"
    	window.FormModal = ModalForm;
    </script>
    @endpush

@endonce