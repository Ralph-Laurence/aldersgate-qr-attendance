var attendanceDT = undefined;
var pageLengthDT = undefined;

$(function () 
{
    // Use this to set maximum displayed pagination numbers
    jQuery.fn.dataTableExt.pager.numbers_length = 5;
    
    attendanceDT = new DataTable('.students-table', 
    {
        pagingType      : "full_numbers",    // Show the First, Previousm Next and Last pagination buttons
        //lengthChange    : false,
        searching       : false,
        autoWidth       : false,
    });

    pageLengthDT = '.dataTables_length#DataTables_Table_0_length';

    restylePaginationLength(pageLengthDT);
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