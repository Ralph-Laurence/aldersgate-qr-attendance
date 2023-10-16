@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modals/form-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modals/modal-forms.css') }}">
@endpush
<div class="modal fade form-modal" id="addEditStudentModal" tabindex="-1" aria-labelledby="form-modalModalLabel"
	aria-hidden="true" data-mdb-backdrop="static" data-mdb-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h6 class="modal-title user-select-none" id="form-modalModalLabel">
					<i class="fas fa-pen p-2 rounded-circle form-modal-icon me-2"></i>
					<span class="modal-title-text form-modal-title">{{ "Add new student" }}</span>
				</h6>
                <button type="button" class="form-modal-close" data-mdb-dismiss="modal" aria-label="Close">
					<i class="fas fa-xmark"></i>
				</button>
			</div>
			<div class="modal-body modal-body-content border-0 form-modal-content">
				
				<form action="" method="post" class="modal-forms">
					<div id="carousel-student-forms" class="carousel slide" data-mdb-keyboard="false" data-mdb-interval="false" style="min-height: 200px;">
						<div class="carousel-inner">
							<div class="carousel-item active">
								{{-- <img src="https://mdbcdn.b-cdn.net/img/new/slides/041.webp" class="d-block w-100" alt="Wild Landscape" /> --}}
								<div class="container-fluid pt-1">
									<div class="row mb-2">
										<div class="col-5 d-flex align-items-center">{{ "* Firstname" }}</div>
										<div class="col d-flex align-items-center">
											<div class="input-text w-100">
												<input type="text" name="input-fname" id="input-fname">
												<i class="fa-solid fa-circle-xmark ms-2 input-trailing-icon"></i>
											</div>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-5 d-flex align-items-center">{{ "* Middlename" }}</div>
										<div class="col d-flex align-items-center">
											<div class="input-text w-100">
												<input type="text" name="input-mname" id="input-mname">
												<i class="fa-solid fa-circle-xmark ms-2 input-trailing-icon"></i>
											</div>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-5 d-flex align-items-center">{{ "* Lastname" }}</div>
										<div class="col d-flex align-items-center">
											<div class="input-text w-100">
												<input type="text" name="input-lname" id="input-lname">
												<i class="fa-solid fa-circle-xmark ms-2 input-trailing-icon"></i>
											</div>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-5 d-flex align-items-center">{{ "* Student No." }}</div>
										<div class="col d-flex align-items-center">
											<div class="input-text w-100">
												<input type="text" name="input-lname" id="input-lname">
												<i class="fa-solid fa-circle-xmark ms-2 input-trailing-icon"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="carousel-item">
								TEST
							</div>
							<div class="carousel-item">
								OTHER
							</div>
							{{-- <div class="carousel-item">
								<img src="https://mdbcdn.b-cdn.net/img/new/slides/042.webp" class="d-block w-100" alt="Camera" />
							</div>
							<div class="carousel-item">
								<img src="https://mdbcdn.b-cdn.net/img/new/slides/043.webp" class="d-block w-100" alt="Exotic Fruits" />
							</div> --}}
						</div>
						{{-- <button class="carousel-control-prev" type="button" data-mdb-target="#carouselExampleControls"
							data-mdb-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Previous</span>
						</button>
						<button class="carousel-control-next" type="button" data-mdb-target="#carouselExampleControls"
							data-mdb-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="visually-hidden">Next</span>
						</button> --}}
					</div>
					<div class="carousel-mini-map d-flex align-items-center justify-content-between position-relative px-3 pb-4">
						<button type="button" class="mini-map-slider mini-map-left" data-label="Back">
							<i class="fa-solid fa-circle-chevron-left"></i>
						</button>
						<button type="button" class="mini-map-pin active" data-label="Basic Info" data-frame-order="0">
							<i class="fa-solid"></i>
						</button>
						<button type="button" class="mini-map-pin" data-label="Contact" data-frame-order="1">
							<i class="fa-solid"></i>
						</button>
						<button type="button" class="mini-map-pin" data-label="Misc" data-frame-order="2">
							<i class="fa-solid"></i>
						</button>
						<button type="button" class="mini-map-slider mini-map-right" data-label="Next">
							<i class="fa-solid fa-circle-chevron-right"></i>
						</button>
					</div>
				</form>

            </div>
			<div class="modal-footer border-0 form-modal-buttons">
				<button type="button" class="btn btn-negative shadow-0" data-mdb-dismiss="modal">{{ "Cancel" }}</button>
				<button type="button" class="btn btn-positive shadow-0" data-mdb-dismiss="modal">{{ "Save" }}</button>
			</div>
		</div>
	</div>
</div>