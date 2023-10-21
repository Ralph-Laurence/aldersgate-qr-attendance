var studentsDT = undefined;
var pageLengthDT = undefined;

var studentFormModal = undefined;
var formCarousel = undefined;
var formCarouselFrameIndex = 0;
var formCarouselFrameCount = 0;

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
    yearSelect = new FlatSelect('#input-year-level');

    studentFormModal = new FormModal($('#addEditStudentModal'));
  
    if ($(".has-error").length > 0)
        showAddStudentForm();

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

    $(".btn-add-student").on('click', () => showAddStudentForm());

    studentFormModal.onNegativeClicked(() => {
        resetStudentForm();
    });

    studentFormModal.onCanceled(() => {
        resetStudentForm();
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

function showAddStudentForm() 
{
    studentFormModal.present({
        title: 'Add new student'
    });
}

function resetStudentForm()
{
    studentFormModal.resetForm();
    courseSelect.reset();
    yearSelect.reset();
}