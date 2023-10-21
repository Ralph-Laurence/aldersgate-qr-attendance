import { ModalsBase } from "../modals/modals-base.js";

export class ModalForm extends ModalsBase
{
    resetForm(onReset)
    {
        $(this.domElement).find('.modal-body form').trigger('reset');

        if (onReset && typeof onReset === 'function')
            onReset();
    }

    present(options) 
    {
        if (options)
            this.bindOptions(options);
        
        this.show();
    }
}