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
					<span class="modal-title-text messagebox-title">{{ "Title" }}</span>
				</h6>
				<button type="button" class="messagebox-close modal-close" data-mdb-dismiss="modal" aria-label="Close">
					<i class="fas fa-xmark"></i>
				</button>
			</div>
			<div class="modal-body messagebox-content border-0">{{ "Content" }}</div>
			<div class="modal-footer border-0 messagebox-buttons">
				<x-flat-button as="btn-negative" theme="default" text="Cancel" data-mdb-dismiss="modal" class="d-none"/>
				<x-flat-button as="btn-positive" theme="default" text="OK" data-mdb-dismiss="modal"/>
			</div>
		</div>
	</div>
</div>
{{-- 
@push('scripts')
<script type="module">
	
  	import { MessageBox } from "{{ asset('js/modals/messagebox.js') }}";

	$(document).ready(function() {
		window.msgBox = new MessageBox($('#messagebox'));
	});
</script>
@endpush --}}