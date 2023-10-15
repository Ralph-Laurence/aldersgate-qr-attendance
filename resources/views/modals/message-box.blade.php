@push('styles')
<link rel="stylesheet" href="{{ asset('css/modals/messagebox.css') }}">
@endpush
<div class="modal fade messagebox" id="messagebox" tabindex="-1" aria-labelledby="messageboxModalLabel"
	aria-hidden="true" data-mdb-backdrop="static" data-mdb-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h6 class="modal-title user-select-none" id="messageboxModalLabel">
					<i class="fas p-2 rounded-circle messagebox-icon me-2"></i>
					<span class="messagebox-title">{{ "Title" }}</span>
				</h6>
				<button type="button" class="messagebox-close" data-mdb-dismiss="modal" aria-label="Close">
					<i class="fas fa-xmark"></i>
				</button>
			</div>
			<div class="modal-body messagebox-content">{{ "Content" }}</div>
			<div class="modal-footer messagebox-buttons">
				<button type="button" class="btn btn-negative shadow-0" data-mdb-dismiss="modal">{{ "Cancel" }}</button>
				<button type="button" class="btn btn-positive shadow-0" data-mdb-dismiss="modal">{{ "OK" }}</button>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script src="{{ asset('js/modals/messagebox.js') }}"></script>
<script>
	var isDOMReady = false;
	var msgBox = null;

	$(function()
	{
	    isDOMReady = true;

		msgBox = new MessageBox($('#messagebox'));
	});
</script>
@endpush