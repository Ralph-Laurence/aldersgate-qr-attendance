import { FlatPagerLength } from "../../components/flat-pager-length.js";
import { SharedProps } from "../shared/sharedprops.js";

window.props = new SharedProps();

let attendancePopover = null;

$(document).on('domReady', function () 
{
    initializeDataTable('.attendance-table');

    new FlatSelect('#search-filter');

    initPopOver_createAttendance();

    bindEvents();
});

function populateForm(data, then)
{
    var $form = props.$crudFormModalDom;

    $form.find('#input-student-no').val(data.studentNo);
    $form.find('#input-fname').val(data.firstname);
    $form.find('#input-mname').val(data.middlename);
    $form.find('#input-lname').val(data.lastname);
    $form.find('#input-email').val(data.email);
    $form.find('#input-contact').val(data.contact);
    $form.find('#input-birthday').val(data.birthday);
    $form.find('#student-key').val(data.studentKey);

    // This will trigger a custom event called 'onPopulateExtra' and passes
    // an output param 'data' which comes from the 'data' parameter that
    // was used above to populate the form
    $(document).trigger('onPopulateExtra', [data]);

    if (typeof then === 'function')
        then();
}

function initializeDataTable(tableId) 
{
    // Use this to set maximum displayed pagination numbers
    jQuery.fn.dataTableExt.pager.numbers_length = 5;

    var dt = $(tableId).DataTable({
        pagingType: "full_numbers",    // Show the First, Previousm Next and Last pagination buttons
        //lengthChange    : false,
        searching: false,
        autoWidth: false,
        'order': []
    });

    // Restyle pager length filter
    var pagerLength = new FlatPagerLength(tableId);
    pagerLength.applyTo($('.pagination-length-control'));

    return {
        'dataTable': dt,
        'lengthFilter': pagerLength
    };
}

function bindEvents()
{
    $(document)
        .on('click', '.attendance-table .btn-delete',   (e) => handleDelete(e))
        .on('click', '.attendance-table .btn-edit',     (e) => handleEdit(e))
        .on('click', '.popover-content .popover-close', () => closeAttendancePopover())
        .on('click', '#btn-add-record-manual', () => 
        {
            closeAttendancePopover();
            props.setActionCreate();
            props.showCrudForm();
        })
        .on('click', function (e) 
        {
            var popoverTrigger = $('#btn-add-record-manual');
            // Don't hide if the click event was in the popover content
            if (!popoverTrigger.is(e.target) && popoverTrigger.has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                closeAttendancePopover();
            }
        });

    $(window).on('blur', function() {
        closeAttendancePopover()
    });

    props.crudFormModal.onPositiveClicked(() => 
    {
        if (!props.validateEntries())
            return;

        props.crudFormModal.submitForm();
    });

    props.crudFormModal.onClosed(() => 
    {
        var lastAction = $('#form-action').val();

        if (lastAction == props.ACTION_EDIT && !props.crudFormModal.isDirty())
        {
            props.cleanupForm();
            return;
        }

        if (props.isFormFilled())
        {
            console.warn('form is dirty');

            var showLastForm = () => 
            {
                props.setFormAction(lastAction);
                props.showCrudForm();
            };

            props.messageBox.showWarning("You have unsaved changes. Are you sure you want to close?",
                {
                    positiveButtonClick: () => props.cleanupForm(),
                    negativeButtonClick: () => showLastForm(),
                    onCanceled: () => showLastForm(),
                    useNegativeButton: true,
                });
        }
        else
        {
            console.warn('clean like legit');
            props.setActionNone();
        }
    });
}

function handleDelete(eventTarget)
{
    var dataTarget = $(eventTarget.currentTarget).closest('.record-actions').find('.data-target').val();

    if (!dataTarget)
        props.messageBox.showDanger(props.ERR_COMMON);

    try 
    {
        let data = JSON.parse(dataTarget);
        let msg = `Heads up! You are about to remove the student "<span class="emphasized">${data.name}</span>" from the records, which includes erasing their attendance history.<br><br>Do you want to proceed?`;

        props.messageBox.showWarning(msg,
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
        console.log(error)
        props.messageBox.showDanger(props.ERR_COMMON);
    }
}

function handleEdit(eventTarget)
{
    var $row = $(eventTarget.currentTarget).closest('tr').find('.row-data').val();
    var dataTarget = $(eventTarget.currentTarget).closest('.record-actions').find('.data-target').val();

    if (!dataTarget)
        props.messageBox.showDanger(props.ERR_COMMON);

    try
    {
        let obj = JSON.parse($row);
        let target = JSON.parse(dataTarget);

        obj['studentKey'] = target.key;

        props.setActionEdit();
        populateForm(obj, props.showCrudForm());
    }
    catch (ex)
    {
        var err = "Unable to edit this record because the data couldn't be read. Please reload the page and try again.<br><br>If this message persists, contact the administrator."

        props.messageBox.showDanger(err, {
            title: 'Failure'
        });
    }
}

function initPopOver_createAttendance()
{
    var content = $('#attendance-pop-over-container').children().first().clone();

    const exampleEl = document.getElementById('btn-popover-add-record');
    const options =
    {
        html:    true,
        title:   'Add new attendance',
        content: content,
        trigger: "click"
    }

    attendancePopover = new mdb.Popover(exampleEl, options);
    window.popover = attendancePopover;
}

function closeAttendancePopover()
{
    if (attendancePopover == null || attendancePopover == undefined)
        return;

    attendancePopover.hide();
}

window.fetchStudentDatalist = function(url, callback)
{
    var csrf = $('.ajax_csrf_token').val();

    if (!csrf)
    {
        console.warn('No CSRF Token. Request will be cancelled');
        return;
    }

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': csrf },
        error: (xhr) => console.warn(xhr)
    });

    $.get(url, function(data) {
        callback(data);
    });
}