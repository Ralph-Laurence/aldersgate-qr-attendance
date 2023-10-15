@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modals/forms-lightbox.css') }}">
@endpush
<div class="modal fade forms-lightbox" id="forms-lightbox" tabindex="-1" aria-labelledby="forms-lightboxModalLabel"
	aria-hidden="true" data-mdb-backdrop="static" data-mdb-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h6 class="modal-title user-select-none" id="forms-lightboxModalLabel">
					<i class="fas fa-pen p-2 rounded-circle forms-lightbox-icon me-2"></i>
					<span class="modal-title-text forms-lightbox-title">{{ "Title" }}</span>
				</h6>
                <button type="button" class="forms-lightbox-close" data-mdb-dismiss="modal" aria-label="Close">
					<i class="fas fa-xmark"></i>
				</button>
			</div>
			<div class="modal-body modal-body-content border-0 forms-lightbox-content">{{ "Content" }}</div>
			<div class="modal-footer border-0 forms-lightbox-buttons">
				<button type="button" class="btn btn-negative shadow-0" data-mdb-dismiss="modal">{{ "Cancel" }}</button>
				<button type="button" class="btn btn-positive shadow-0" data-mdb-dismiss="modal">{{ "OK" }}</button>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script type="module">
  
  	import { FormsLightBox } from "{{ asset('js/modals/forms-lightbox.js') }}";

	$(function() {
		window.formsLightBox = new FormsLightBox($('#forms-lightbox'));
	});
</script>
@endpush