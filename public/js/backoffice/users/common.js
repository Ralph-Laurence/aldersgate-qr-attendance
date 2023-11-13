import { FlatPagerLength } from "../../components/flat-pager-length.js";
import { SharedProps } from "../shared/sharedprops.js";

window.props = new SharedProps();

$(document).on('domReady', function () 
{ 
    initializeDataTable( '.users-table' );
    new FlatSelect('#search-filter');

    bindEvents();
});

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

function bindEvents()
{
    $("#btn-add-record").on('click', () => 
    {
        props.setActionCreate();
        props.showCrudForm();
    });

    $(document)
        .on('click', '.users-table .btn-delete', (e) => handleDelete(e))
        .on('click', '.users-table .btn-edit',   (e) => handleEdit(e));

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
                onCanceled:          () => showLastForm(),
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
        let msg  = `Heads up! You are about to remove the user "<span class="emphasized">${data.name}</span>" from the records.<br><br>Do you want to proceed?`;

        props.messageBox.showWarning(msg,
            {
                positiveButtonText: 'Yes',
                positiveButtonClick: () => 
                {
                    var $deleteForm = $("#deleteform");

                    $deleteForm.find("#user-key").val(data.key);
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
    var $row        = $(eventTarget.currentTarget).closest('tr').find('.row-data').val();
    var dataTarget  = $(eventTarget.currentTarget).closest('.record-actions').find('.data-target').val();

    if (!dataTarget)
        props.messageBox.showDanger(props.ERR_COMMON);

    try
    {
        let obj    = JSON.parse($row);
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