import { ModalsBase } from "./modals-base.js";

export class MessageBox extends ModalsBase
{
    CLASS_MSG_PRIMARY   = 'messagebox-primary';
    CLASS_MSG_WARN      = 'messagebox-warn';
    CLASS_MSG_DANGER    = 'messagebox-danger';


    // constructor(modalElement)
    // {
    //     super(modalElement);
    // }

    //
    // MODAL USAGE
    //
    showInfo(message, options)
    {
        this.validateInstance();
        this.bindOptions(options);

        if (!options || !options.hasOwnProperty(this.PROP_KEY_MODAL_TITLE))
            this.setTitle('Information');

        this.setContent(message);
        this.updateClass(this.CLASS_MSG_PRIMARY);

        this.show();
    }

    showWarning(message, options)
    {
        this.validateInstance();
        this.bindOptions(options);

        if (!options || !options.hasOwnProperty(this.PROP_KEY_MODAL_TITLE))
            this.setTitle('Warning');
    
        this.setContent(message);
        this.updateClass(this.CLASS_MSG_WARN);
    
        this.show();
    }

    showDanger(message, options)
    {
        this.validateInstance();
        this.bindOptions(options);

        if (!options || !options.hasOwnProperty(this.PROP_KEY_MODAL_TITLE))
            this.setTitle('Alert');
    
        this.setContent(message);
        this.updateClass(this.CLASS_MSG_DANGER);
    
        this.show();
    }

    updateClass(newClass)
    {
        var classes = [this.CLASS_MSG_PRIMARY, this.CLASS_MSG_WARN, this.CLASS_MSG_DANGER];
        var $modalRoot = $(this.domElement);

        $.each(classes, function(index, selectorClass){
            $modalRoot.removeClass(selectorClass);
        });

        $modalRoot.addClass(newClass);
    }
}

/**
 * SAMPLE USAGE
 */

/*
msgBox.showInfo('test information', {
    title: 'Upload',
    positiveButtonClick: () => {
        alert('ok clicked');
    },
    useNegativeButton: true,
    negativeButtonClick: () => {
        alert('cancel clicked');
    },
    onClosed: () => console.log('modal was closed')
});
*/