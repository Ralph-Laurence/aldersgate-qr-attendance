import { MessageBox } from "../../modals/messagebox.js";
import { FlatPagerLength } from "../../components/flat-pager-length.js";
 
const ACTION_NONE       = 0;
const ACTION_CREATE     = 1;
const ACTION_EDIT       = 2;
const ERR_COMMON        = "This action can't be completed. Please reload the page and try again.";

const studentForm       = '#studentFormModal';
const _studentsTable    = '.students-table';

var studentsDT          = undefined;
var studentFormModal    = undefined;

var messageBox          = null;

var routeCreate         = undefined;
var routeEdit           = undefined;
 
var formAction          = ACTION_NONE;

$(document).ready(function () 
{ 
    initializeDataTable(_studentsTable);

    studentFormModal = new FormModal($(studentForm));
    studentFormModal.closeOnPositive(false);

    messageBox       = new MessageBox($('#messagebox'));

    routeCreate      = $('#form-action-container-store').val();
    routeEdit        = $('#form-action-container-update').val();

    formAction       = $('#studentFormModal #form-action').val();

    materialDatePicker('#input-birthday');
    new FlatSelect('#search-filter');

    setFormAction(formAction);

    if (studentFormModal.hasErrors())
    {
        studentFormModal.setDirty();
        showStudentForm();
    }

    bindEvents();

    loadFlashMessage();
});
 
function populateForm(mainForm, data, then)
{
    var $form = $(mainForm);

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

function isFormFilled(formModal) 
{
    // The filter function will be used to count the number of non-empty input elements. 
    // This eliminates the need for the 'var counter = 0' variable and the $.each loop.
    // This version could be slightly faster because it stops searching as soon as it 
    // finds a non-empty input element or a visible error label.
    const inputs = $(formModal.getForm()).find('.flat-controls input').filter(function() 
    {
        return $(this).val();
    });

    // If there are error labels visible, count them as dirty
    const hasErrors = $('.has-error').length > 0;

    // Directly checks whether there are non-empty input elements or visible error labels.
    return inputs.length > 0 || hasErrors;
}

function validateEntries(formModal)
{
    var requiredFields = formModal.getForm().find('input[required]');
    var errorCount = 0;

    $.each(requiredFields, (i, f) => 
    {
        var $root  = $(f).closest('.flat-controls');
        var $label = $root.find('.error-label');
        var $text  = $root.find('.input-text');
        var $alias = $root.data('alias');

        if ($(f).val())
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

function loadFlashMessage()
{
    var flashedMessage = $("#flash-message").val().trim();

    if (flashedMessage == '' || flashedMessage == undefined || flashedMessage == null)
        return;

    try 
    {
        var content = JSON.parse(flashedMessage);

        switch (content.showIn)
        {
            case 'modal':
                messageBox.showInfo(message, { title: title });
                break;

            case 'toast':
                showToast(content.response, '', { type: content.type });
                break;
        }
    }
    catch (error) 
    {
        console.warn('Unable to read flashed message.' + error);
    }
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
        'dataTable'     : dt,
        'lengthFilter'  : pagerLength
    };
}

function handleDelete(eventTarget)
{
    var dataTarget = $(eventTarget.currentTarget).closest('.record-actions').find('.data-target').val();

    if (!dataTarget)
        messageBox.showDanger(ERR_COMMON);

    try 
    {
        let data = JSON.parse(dataTarget);
        let msg  = `Heads up! You are about to remove the student "<span class="emphasized">${data.name}</span>" from the records, which includes erasing their attendance history.<br><br>Do you want to proceed?`;

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
        console.log(error)
        messageBox.showDanger(ERR_COMMON);
    }
}

function bindEvents()
{
    $("#btn-add-record").on('click', () => 
    {
        setFormAction(ACTION_CREATE);
        showStudentForm();
    });

    $(document)
        .on('click', '.students-table .btn-delete', (e) => handleDelete(e))
        .on('click', '.students-table .btn-edit',   (e) => handleEdit(e));

    studentFormModal.onPositiveClicked(() => 
    {
        if (!validateEntries(studentFormModal))
            return;

        studentFormModal.submitForm();
    });

    studentFormModal.onClosed(() => 
    {
        var lastAction = $('#form-action').val();

        if (lastAction == ACTION_EDIT && !studentFormModal.isDirty())
        {
            cleanupForm();
            return;
        }

        if (isFormFilled(studentFormModal))
        {
            console.warn('form is dirty');

            var showLastForm = () => 
            {
                setFormAction(lastAction);
                showStudentForm();
            };

            messageBox.showWarning("You have unsaved changes. Are you sure you want to close?",
            {
                positiveButtonClick: () => cleanupForm(),
                negativeButtonClick: () => showLastForm(),
                onCanceled:          () => showLastForm(),
                useNegativeButton: true,
            });
        }
        else
        {
            console.warn('clean like legit');
            setFormAction(ACTION_NONE);
        }
    });
}

/* #region FORM LOGIC */

function showStudentForm() 
{
    var formTitles = 
    {
        [ACTION_CREATE]:  'Add new student',
        [ACTION_EDIT  ]:  'Edit student',
        [ACTION_NONE  ]:  'Student Form'
    };

    var title = formTitles[ACTION_NONE];
 
    if (formAction in formTitles)
        title = formTitles[formAction];
 
    studentFormModal.present({ title: title });
}

function handleEdit(eventTarget)
{
    var $row        = $(eventTarget.currentTarget).closest('tr').find('.row-data').val();
    var dataTarget  = $(eventTarget.currentTarget).closest('.record-actions').find('.data-target').val();

    if (!dataTarget)
        messageBox.showDanger(ERR_COMMON);

    try
    {
        let obj    = JSON.parse($row);
        let target = JSON.parse(dataTarget);

        obj['studentKey'] = target.key;

        setFormAction(ACTION_EDIT);
        populateForm(studentForm, obj, showStudentForm());
    }
    catch (ex)
    {
        var err = "Unable to edit this record because the data couldn't be read. Please reload the page and try again.<br><br>If this message persists, contact the administrator."

        messageBox.showDanger(err, {
            title: 'Failure'
        });
    }
}

function setFormAction(action)
{
    formAction = parseInt(action) || 0;

    $('#studentFormModal #form-action').val(formAction);

    switch (formAction)
    {
        case ACTION_CREATE:
            studentFormModal.setSubmitAction(routeCreate);
            break;

        case ACTION_EDIT:
            studentFormModal.setSubmitAction(routeEdit);
            break;

        case ACTION_NONE:
        default:
            studentFormModal.clearSubmitAction();
            break;
    }
}

function cleanupForm()
{
    studentFormModal.resetForm();

    $(document).trigger('onCleanupForm');

    setFormAction(ACTION_NONE);
}

/* #endregion */

