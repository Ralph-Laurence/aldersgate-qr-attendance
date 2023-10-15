export class ModalsBase
{
    constructor(modalElement)
    {        
        if (!modalElement.get(0))
            throw new Error('Modal element is null');
 
        this.domElement         = modalElement.get(0);
        this.modalInstance      = new mdb.Modal(this.domElement);

        this.PROP_KEY_MODAL_CLOSED          = 'onClosed';
        this.PROP_KEY_MODAL_TITLE           = 'title';

        this.PROP_KEY_POSITIVE_BUTTON       = 'positiveButtonText';
        this.PROP_KEY_USE_NEGATIVE_BUTTON   = 'useNegativeButton';
        this.PROP_KEY_NEGATIVE_BUTTON       = 'negativeButtonText';
        this.PROP_KEY_NEGATIVE_BUTTON_CLICK = 'negativeButtonClick';
        this.PROP_KEY_POSITIVE_BUTTON_CLICK = 'positiveButtonClick';
 
        this.initElements();
        this.bindEvents();
    }

    //
    // MODAL INTERNAL LOGICS
    //
    initElements()
    {
        var el_title        = $(this.domElement).find('.modal-title-text');
        var el_content      = $(this.domElement).find('.modal-body');
        var el_posBtn       = $(this.domElement).find('.btn-positive');
        var el_negBtn       = $(this.domElement).find('.btn-negative');

        if (!el_title)
            throw new Error('Missing title element for modal.');
 
        if (!el_content)
            throw new Error('Missing content element for modal.');

        if (!el_posBtn)
            throw new Error('Missing positive button for modal.');
  
        if (!el_negBtn)
            throw new Error('Missing negative button for modal.');

        this.modalTitle     = el_title;
        this.modalBody      = el_content;
        this.positiveButton = el_posBtn;
        this.negativeButton = el_negBtn;
    }

    bindEvents()
    {
        $(this.positiveButton).on('click', () => {
            
            if (this.ev_positiveBtnClick)
                this.ev_positiveBtnClick();
        });

        $(this.negativeButton).on('click', () => {
          
            if (this.ev_negativeBtnClick)
                this.ev_negativeBtnClick();
        });

        $(this.domElement)[0].addEventListener('hidden.bs.modal',(e) => {
            
            this.ev_negativeBtnClick = null;
            this.ev_positiveBtnClick = null;

            if (this.ev_modalClosed)
                this.ev_modalClosed();
        });
    }

    bindOptions(options)
    {
        if (!options)
            return;

        //
        // Modal Title
        //
        if (options.hasOwnProperty(this.PROP_KEY_MODAL_TITLE))
            $(this.modalTitle).text(options[this.PROP_KEY_MODAL_TITLE]);

        //
        // Button Texts
        //
        if (options.hasOwnProperty(this.PROP_KEY_POSITIVE_BUTTON))
            $(this.positiveButton).text(options[this.PROP_KEY_POSITIVE_BUTTON]);

        if (options.hasOwnProperty(this.PROP_KEY_NEGATIVE_BUTTON))
            $(this.negativeButton).text(options[this.PROP_KEY_NEGATIVE_BUTTON]);
 
        //
        // Button Events
        //
        if (options.hasOwnProperty(this.PROP_KEY_POSITIVE_BUTTON_CLICK))
            this.ev_positiveBtnClick = options[this.PROP_KEY_POSITIVE_BUTTON_CLICK];

        if (options.hasOwnProperty(this.PROP_KEY_NEGATIVE_BUTTON_CLICK))
            this.ev_negativeBtnClick = options[this.PROP_KEY_NEGATIVE_BUTTON_CLICK];

        //
        // Should we use negative button or not
        //
        if (options.hasOwnProperty(this.PROP_KEY_USE_NEGATIVE_BUTTON))
        {
            var optionNegBtn = options[this.PROP_KEY_USE_NEGATIVE_BUTTON];

            if (optionNegBtn === true)
                $(this.negativeButton).toggleClass('d-none', false);
            else 
                $(this.negativeButton).toggleClass('d-none', true);
        }

        //
        // Modal closed event
        //
        if (options.hasOwnProperty(this.PROP_KEY_MODAL_CLOSED))
            this.ev_modalClosed = options[this.PROP_KEY_MODAL_CLOSED];
    } 

    checkElementsExists()
    {
        var elems = [this.modalTitle, this.modalBody, this.positiveButton, this.negativeButton];
        var passed = true;

        $.each(elems, function(index, value)
        {
            if (value === null && value === undefined && value.length === 0)
            {
                passed = false;
                return false;
            }
        });

        return passed;
    }

    validateInstance()
    {
        if (!this.modalInstance || !this.checkElementsExists)
            throw new Error("Modal isn't initialized");
    }

    updateClass(newClass)
    {
        var classes = [this.CLASS_MSG_INFO, this.CLASS_MSG_WARN, this.CLASS_MSG_DANGER];
        var $domEl = $(this.domElement);

        $.each(classes, function(index, selectorClass){
            $domEl.removeClass(selectorClass);
        });

        $domEl.addClass(newClass);
    }

    //
    // CONSUMER END LOGICS
    //
    show() 
    { 
        //this.validateInstance();
        this.modalInstance.show();
    }

    close() 
    {
        //this.validateInstance();
        this.modalInstance.hide();
    }

    setContent(html)
    {
        //this.validateInstance();
        $(this.modalBody).html(html);
    }

    setTitle(title)
    {
        //this.validateInstance();
        $(this.modalTitle).text(title);
    }
}