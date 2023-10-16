import { FormModal } from "../modals/form-modal.js";

var studentsDT = undefined;
var pageLengthDT = undefined;

var addEditStudentModal = undefined;
var formCarousel = undefined;

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
    });

    pageLengthDT = '.dataTables_length#DataTables_Table_0_length';

    restylePaginationLength(pageLengthDT);
  
    addEditStudentModal = new FormModal($('#addEditStudentModal'));
    formCarousel = new mdb.Carousel($('#carousel-student-forms'));
    window.frmCsl = formCarousel;
    bindEvents();
});

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

        $(target).find('ul.dropdown-menu').append(`<li><a class="dropdown-item page-length-item ${initallySelected}" onclick="changePageLength(this, ${val})">${val}</a></li>`);
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

function bindEvents()
{
    // var content = $('.lightbox-content-add-student');
    // window.formsLightBox.appendContent(content);

    // $(".btn-add-student").on('click', function() 
    // {
    //     window.formsLightBox.setTitle('Add new student');
    //     window.formsLightBox.show();
    // });
    $(".btn-add-student").on('click', function() 
    {
        addEditStudentModal.show();
    });

    $('.carousel-mini-map .mini-map-left').on('click', function() 
    {
        formCarousel.prev();
    });

    $('.carousel-mini-map .mini-map-right').on('click', function() 
    {
        formCarousel.next();
    });

    $('#carousel-student-forms').on('slid.bs.carousel', (props) => 
    {
        // Get the frame index we scrolled to
        var scrolledFrame = props.to;

        // Clear the active minimaps then mark the scrolled minimap
        $.each($('.carousel-mini-map button'), function(i, btn) 
        {
            $(btn).removeClass('active'); 
        });

        // Find the button with data attribute 'frame-order' and add class 'active'
        $('.carousel-mini-map button').filter(function ()
        {
            return $(this).data('frame-order') == scrolledFrame;
        }).addClass('active');
    });

    // Jump carousel to specific frame
    $('.carousel-mini-map button.mini-map-pin').on('click', function()
    {
        formCarousel.to($(this).data('frame-order'))
    });
}