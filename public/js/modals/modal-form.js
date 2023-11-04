import { ModalsBase } from "../modals/modals-base.js";

export class ModalForm extends ModalsBase
{
    __isDirty = false;

    MODAL_ACTION_NONE   = 0;
    MODAL_ACTION_CREATE = 1;
    MODAL_ACTION_EDIT   = 2;

    // pass the param 'modalElement' into Base Class 'ModalsBase'
    constructor(modalElement)
    {
        super(modalElement);

        $( (e) =>
        {
            $(this.getForm()).find('.flat-controls .main-control').on('input', (e) => 
            {
                console.warn('having dirt issue');
                this.setDirty();
            });
        });
    }

    isDirty() 
    {
        return this.__isDirty;
    }

    setDirty()
    {
        this.__isDirty = true;
    }

    clearDirty() 
    {
        this.__isDirty = false;
    }

    getForm()
    {
        return $(this.domElement).find('.modal-body form');
    }

    setSubmitAction(route)
    {
        var $form = this.getForm();
        $form.attr('action', route);
    }

    clearSubmitAction()
    {
        var $form = this.getForm();
        $form.removeAttr('action');
    }

    hasErrors()
    {
        var errors = $(this.getForm()).find('.flat-controls .has-error').length > 0;
        return errors;
    }

    resetForm(hardReset)
    {
        hardReset = hardReset || true;
        
        if (hardReset) // 
        {
            $(this.getForm()).find('.flat-controls .main-control').val('')

            console.warn('performing hard reset...');
        }
        else
        {
            this.getForm().trigger('reset');
            console.warn('basic form reset');
        } 

        var $form = $(this.getForm())
        $form.find('.flat-controls .has-error').removeClass('has-error');
        $form.find('.flat-controls .error-label').text('');

        this.clearDirty();
        this.clearSubmitAction();
    }

    submitForm()
    {
        $(this.getForm()).trigger('submit');
    }

    present(options) 
    {
        if (options)
            this.bindOptions(options);

        this.show();
    }

    closeOnPositive(autoClose)
    { 
        if (!autoClose)
        {
            $(this.positiveButton).removeAttr('data-mdb-dismiss');
            return;
        }

        $(this.positiveButton).attr('data-mdb-dismiss', 'modal');
    }

    closeOnNegative(autoClose)
    { 
        if (!autoClose)
        {
            $(this.negativeButton).removeAttr('data-mdb-dismiss');
            return;
        }

        $(this.negativeButton).attr('data-mdb-dismiss', 'modal');
    }
}