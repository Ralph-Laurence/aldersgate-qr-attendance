import { FlatPagerLength } from "../components/flat-pager-length.js";
import { MessageBox } from "../modals/messagebox.js";

var studentsDT = undefined;
var studentFormModal = undefined;

var needsHardReset = false;

var courseSelect        = null;
var yearSelect          = null;
var messageBox          = null;
var pageLengthFilter    = null;

$(document).ready(function()
{
    // Use this to set maximum displayed pagination numbers
    jQuery.fn.dataTableExt.pager.numbers_length = 5;
    
    studentsDT = $('.students-table').DataTable(
    {
        pagingType      : "full_numbers",    // Show the First, Previousm Next and Last pagination buttons
        //lengthChange    : false,
        searching       : false,
        autoWidth       : false,
        'order'         : [] 
    });
    
    messageBox = new MessageBox($('#messagebox'));
    pageLengthFilter = new FlatPagerLength('.students-table');

    courseSelect = new FlatSelect('#input-course');
    yearSelect   = new FlatSelect('#input-year-level');
    
    studentFormModal = new FormModal($('#addEditStudentModal'));
    studentFormModal.closeOnPositive(false);
    
    pageLengthFilter.applyTo( $('.pagination-length-control') );
    window.pager = pageLengthFilter;
    //studentFormModal.closeOnNegative(false); // --> default behaviour closes the modal
    
    materialDatePicker('#input-birthday');

    if (studentFormModal.hasErrors())
    {
        needsHardReset = true;
        studentFormModal.setDirty();
        showStudentForm();
    }
    
    bindEvents();
    
    loadFlashMessage();
});

function bindEvents()
{
    $("#btn-add-student").on('click', () => showStudentForm());

    studentFormModal.onPositiveClicked(() => 
    {
        if (!validateEntries())
            return;

        studentFormModal.submitForm();
    });

    studentFormModal.onClosed(() => 
    {
        if (studentFormModal.isDirty())
            handleDirtyFormClose();
        else
        {
            if (studentFormModal.hasErrors())
                resetStudentForm();
        }
    });

    $(document).on('click', '.students-table .btn-delete', function()
    {
        var dataTarget = $(this).closest('.record-actions').find('.data-target').val();
        
        if (!dataTarget)
            messageBox.showDanger("This action can't be completed. Please reload the page and try again.");

        try 
        {
            let data = JSON.parse(dataTarget);
            let msg = `Heads up! You are about to remove the student "<span class="emphasized">${data.name}</span>" from the records, which includes erasing their attendance history.<br><br>Do you want to proceed?`;
            
            messageBox.showWarning(msg, 
            {
                positiveButtonText: 'Yes',
                positiveButtonClick: () => 
                {
                    var $deleteForm = $("#deleteform");

                    $deleteForm.find("#student-key").val(data.key);
                    $deleteForm.trigger('submit');
                },
                useNegativeButton: true,
                negativeButtonText: 'No'
            });
        } 
        catch (error) 
        {
            messageBox.showDanger("This action can't be completed. Please reload the page and try again.");
        }
    });
}

function showStudentForm() 
{
    studentFormModal.present({
        title: 'Add new student'
    });
}

function resetStudentForm()
{
    studentFormModal.resetForm(needsHardReset);
    courseSelect.reset();
    yearSelect.reset();
    studentFormModal.clearDirty();

    if (needsHardReset)
        needsHardReset = false;
}

function validateEntries() 
{
    var requiredFields  = studentFormModal.getForm().find('input[required]');
    var errorCount      = 0;

    $.each(requiredFields, (i, f) => 
    { 
        var $root   = $(f).closest('.flat-controls');
        var $label  = $root.find('.error-label');
        var $text   = $root.find('.input-text');
        var $alias  = $root.data('alias');

        if ( $(f).val() )
        {
            $text.removeClass('has-error');
            $label.text('');
            return true;        // continue next iteration
        }

        switch ($alias)
        {
            case 'text':
                var $placeholder = $text.find('.main-control').attr('placeholder');
                $text.addClass('has-error');
                $label.text((!$placeholder) ? 'Please fill out this field' : `${$placeholder} must be filled out`);
                break;

            case 'select':
                $label.text('Please choose an option');
                break;
            
            default:
                $label.text('');
                break;
        } 

        errorCount++;
    });

    return (errorCount < 1);
}

function handleDirtyFormClose()
{
    messageBox.showWarning("You have unsaved changes. Are you sure you want to close?", 
    {
        positiveButtonClick: () => resetStudentForm(),
        negativeButtonClick: () => showStudentForm(),
        onCanceled:          () => showStudentForm(),
        useNegativeButton:   true,
    });
}

function loadFlashMessage()
{
    var flashedMessage = $("#flash-message").val().trim();

    if (flashedMessage == '' || flashedMessage == undefined || flashedMessage == null)
        return;

    try 
    {
        var content = JSON.parse(flashedMessage);

        switch (content.status)
        {
            case '0':
                messageBox.showInfo(content.response, { title: 'Success!' });    
                break;
        }
    } 
    catch (error) 
    {
        console.warn('Unable to read flashed message.' + error);
    }
}