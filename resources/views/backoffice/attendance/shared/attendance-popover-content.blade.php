<div id="attendance-pop-over-container" class="d-none">
    <div class="popover-content">
        <h6 class="small opacity-75">{{ 'Choose how do you want to create a new attendance record.' }}</h6>
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col px-0 text-start">
                    <x-flat-button as="btn-scan" text="Scan" icon="fa-qrcode"/>
                </div>
                <div class="col px-0 text-end">
                    <x-flat-button as="btn-add-record-manual" theme="warning" text="Create" icon="fa-pen"/>
                </div>
            </div>
            <div class="row">
                <div class="col"></div>
                <div class="col text-end">
                    <button type="button" class="btn btn-tertiary text-capitalize popover-close text-dark opacity-75" data-mdb-dismiss="popover">{{ 'Close' }}</button>
                </div>
            </div>
        </div>
    </div>
</div>