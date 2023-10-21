var studentsDT = undefined;
var pageLengthDT = undefined;

var studentFormModal = undefined;

var needsHardReset = false;

var courseSelect = null;
var yearSelect = null;

$(function () 
{
    // Use this to set maximum displayed pagination numbers
    jQuery.fn.dataTableExt.pager.numbers_length = 5;
    
    studentsDT = new DataTable('.students-table', 
    {
        pagingType      : "full_numbers",    // Show the First, Previousm Next and Last pagination buttons
        //lengthChange    : false,
        searching       : false,
        autoWidth       : false,
        'order'         : [] 
    });

    pageLengthDT = '.dataTables_length#DataTables_Table_0_length';

    restylePaginationLength(pageLengthDT);
   
    courseSelect = new FlatSelect('#input-course');
    yearSelect   = new FlatSelect('#input-year-level');
  
    studentFormModal = new FormModal($('#addEditStudentModal'));
    studentFormModal.closeOnPositive(false);
    
    //studentFormModal.closeOnNegative(false); // --> default behaviour closes the modal
  
    if (studentFormModal.hasErrors())
    {
        needsHardReset = true;
        studentFormModal.setDirty();
        showStudentForm();
    }

    materialDatePicker('#input-birthday');

    bindEvents();
});

function bindEvents()
{
    $(".dropdown-item.page-length-item").on('click', function()
    {
        var length = $(this).data('page-length');

        changePageLength($(this), length);
    });

    $(".btn-add-student").on('click', () => showStudentForm());

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
}

function restylePaginationLength(control)
{
    $(pageLengthDT).hide();

    var target = $('.pagination-length-control');

    // Find the pagination length options, iterate thru each of them, 
    // then copy their values and append them onto the dropdowns
    var options = $(pageLengthDT).find('select > option');
    var select = $(pageLengthDT).find('select');

    $.each(options, function () 
    {
        const val = $(this).val();

        var initallySelected = '';

        if (val == $(select).val())
            initallySelected = 'selected';

        $(target).find('ul.dropdown-menu').append(`<li><a class="dropdown-item page-length-item ${initallySelected}" data-page-length="${val}">${val}</a></li>`);
    });

    // Set default select text
    $('.btn-page-length').text($(options[0]).val());
}

function changePageLength(anchor, length) 
{
    $(pageLengthDT).find('select').val(length).change();
    $('.btn-page-length').text(length);

    $(anchor).closest('ul').find('li > a').removeClass('selected');
    $(anchor).addClass('selected');
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
    msgBox.showWarning("You have unsaved changes. Are you sure you want to close?", 
    {
        positiveButtonClick: () => resetStudentForm(),
        negativeButtonClick: () => showStudentForm(),
        onCanceled:          () => showStudentForm(),
        useNegativeButton:   true,
    });
}