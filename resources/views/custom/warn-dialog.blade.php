@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dialogs/warn-dialog.css') }}">
@endpush
<!-- Error Modal -->
<div class="modal fade" data-mdb-backdrop="static" data-mdb-keyboard="false" id="warnDialog" tabindex="-1"
    aria-labelledby="warnDialogLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content dialog-bg-color position-relative font-accent">
            <div class="backdrop-image"></div>
            <div class="modal-header text-dark border-0">
                <h5 class="modal-title" id="warnDialogLabel">{{ 'Warning' }}</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark"></div>
            <div class="modal-footer bg-white">
                <button type="button" class="btn btn-cancel flat-btn flat-btn-default"
                    data-mdb-dismiss="modal">{{ 'Cancel' }}</button>
                <button type="button" class="btn btn-ok flat-btn flat-btn-warning text-dark"
                    data-mdb-dismiss="modal">{{ 'OK' }}</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script> 
    function showWarnDialog(message, title, okButton, cancelButton, onOK, onCancel) 
    { 
        // Do not show/execute the dialog when the message is not supplied (empty)
        if (message === "" || message === undefined)
            return;

        $("#warnDialog .modal-body").html(message);

        // Set the dialog title only when supplied by user
        if (title !== "" || title !== undefined)
            $("#warnDialog #warnDialogLabel").text(title);

        // Set the text/label for OK button only when supplied by user
        if (okButton !== "" || okButton !== undefined)
            $("#warnDialog .btn-ok").text(okButton);

        // Hide the cancel button when there is no Cancel label/text set to it
        if (cancelButton === "" || cancelButton === undefined)
            $("#warnDialog .btn-cancel").addClass('d-none');
        else
            $("#warnDialog .btn-cancel").text(cancelButton).removeClass('d-none'); 

        // Do action when the OK button was clicked
        if ((onOK !== undefined || onOK !== null) && typeof onOK === 'function')
            $("#warnDialog .btn-ok").on('click', () => onOK());

        // Do action when the Cancel button was clicked
        if ((onCancel !== undefined || onCancel !== null) && typeof onCancel === 'function')
            $("#warnDialog .btn-cancel").on('click', () => onCancel());

        var warnmodal = new mdb.Modal($("#warnDialog"));
        warnmodal.show();
    }

    // Remove (Unbind) all click events on the modal buttons everytime the dialog is closed.
    // This will prevent repetitive action calls for onOK and onCancel
    $(function() 
    {
        $("#warnDialog").on('hidden.bs.modal', function() 
        {
            $("#warnDialog .btn-ok").off('click');
            $("#warnDialog .btn-cancel").off('click');
        });
    });
</script>
@endpush