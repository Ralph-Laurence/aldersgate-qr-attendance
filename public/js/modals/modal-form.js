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

    hasErrors()
    {
        var errors = $(this.getForm()).find('.flat-controls .has-error').length > 0;
        return errors;
    }

    resetForm(hardReset)
    {
        this.getForm().trigger('reset');

        var $form = $(this.getForm());

        if (hardReset === true)
            $form.find('.flat-controls .main-control').removeAttr('value');

        $form.find('.flat-controls .has-error').removeClass('has-error');
        $form.find('.flat-controls .error-label').text('');

        // if (onReset && typeof onReset === 'function')
        //     onReset();
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