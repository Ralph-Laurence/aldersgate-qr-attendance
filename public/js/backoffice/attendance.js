var attendanceDT = undefined;

$(function () 
{
    // Use this to set maximum displayed pagination numbers
    jQuery.fn.dataTableExt.pager.numbers_length = 5;
    
    attendanceDT = new DataTable('.daily-attendance', 
    {
        pagingType      : "full_numbers",    // Show the First, Previousm Next and Last pagination buttons
        lengthChange    : false,
        searching       : false,
        autoWidth       : false,
    });
});