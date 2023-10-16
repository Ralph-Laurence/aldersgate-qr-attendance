@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modals/generic-modal.css') }}">
@endpush
<div class="modal fade generic-modal" id="{{ $id }}" tabindex="-1" aria-labelledby="generic-modalModalLabel"
	aria-hidden="true" data-mdb-backdrop="static" data-mdb-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h6 class="modal-title user-select-none" id="generic-modalModalLabel">
					<i class="fas fa-pen p-2 rounded-circle generic-modal-icon me-2"></i>
					<span class="modal-title-text generic-modal-title">{{ "Title" }}</span>
				</h6>
                <button type="button" class="generic-modal-close" data-mdb-dismiss="modal" aria-label="Close">
					<i class="fas fa-xmark"></i>
				</button>
			</div>
			<div class="modal-body modal-body-content border-0 generic-modal-content">{{ !empty($content) ? $content : '' }}</div>
			<div class="modal-footer border-0 generic-modal-buttons">
				<button type="button" class="btn btn-negative shadow-0" data-mdb-dismiss="modal">{{ "Cancel" }}</button>
				<button type="button" class="btn btn-positive shadow-0" data-mdb-dismiss="modal">{{ "OK" }}</button>
			</div>
		</div>
	</div>
</div>