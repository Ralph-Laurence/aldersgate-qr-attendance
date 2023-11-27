import { FlatInputSuggest } from "../../components/flat-input-suggest.js";

var inputStudentSuggest = null;
var $inputStudentName   = null;
var $inputCourseYear    = null;
var timeInPicker        = null;
var timeOutPicker       = null;

$(function ()
{
    $inputStudentName = $('#input-student-name');
    $inputCourseYear  = $('#input-course-year');

    fetchStudentDatalist(datalistAsyncRoute, (data) => 
    {
        inputStudentSuggest = new FlatInputSuggest('#input-student-no', data);
        
        onBindInputSuggestEvents();

        $('.btn-popover-add-record').prop('disabled', false);

        window.suggest = inputStudentSuggest;
    });

    timeInPicker  = new FlatTimePicker('#input-time-in');
    timeOutPicker = new FlatTimePicker('#input-time-out');

    $(document).on('onCleanupForm', () => 
    {
        inputStudentSuggest.reset();
    });
});

function onBindInputSuggestEvents()
{
    inputStudentSuggest.ItemSelected = (data_attributes) => 
    {
        $inputStudentName.val(data_attributes.name).trigger('input');
        $inputCourseYear.val(`${data_attributes.year}-${data_attributes.course}`).trigger('input');
        inputStudentSuggest.$textInput.val(data_attributes.value);
    };

    inputStudentSuggest.ItemNotFound = () => 
    {
        clearFields();
    };

    inputStudentSuggest.ItemFound = () => 
    {
       $('#crudFormModal .flat-controls.btn-positive').prop('disabled', false);
    };
}

function clearFields()
{
    $inputStudentName.val('');
    $inputCourseYear.val('');
    timeInPicker.clear();
    timeOutPicker.clear();

    $('#crudFormModal .flat-controls.btn-positive').prop('disabled', true);
}