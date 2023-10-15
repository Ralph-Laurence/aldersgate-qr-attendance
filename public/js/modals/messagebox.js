class MessageBox
{
    constructor(modalElement)
    {        
        if (!modalElement.get(0))
            throw new Error('Modal element is null');
 
        this.domElement         = modalElement.get(0);
        this.modalInstance      = new mdb.Modal(this.domElement);

        this.PROP_KEY_MODAL_TITLE = 'title';

        this.PROP_KEY_POSITIVE_BUTTON = 'positiveButtonText';
        this.PROP_KEY_NEGATIVEBUTTON = 'negativeButtonText';

        this.PROP_KEY_USE_NEGATIVE_BUTTON = 'useNegativeButton';

        this.PROP_KEY_NEGATIVE_BUTTON_CLICK = 'negativeButtonClick';
        this.PROP_KEY_POSITIVE_BUTTON_CLICK = 'positiveButtonClick';

        this.CLASS_MSG_INFO      = 'messagebox-info';
        this.CLASS_MSG_WARN      = 'messagebox-warn';
        this.CLASS_MSG_DANGER    = 'messagebox-danger';

        this.initElements();
        this.bindEvents();
    }

    //
    // MODAL INITIALIZATION
    //
    initElements()
    {
        var el_title        = $(this.domElement).find('.messagebox-title');
        var el_content      = $(this.domElement).find('.messagebox-content');
        var el_posBtn       = $(this.domElement).find('.btn-positive');
        var el_negBtn       = $(this.domElement).find('.btn-negative');

        if (!el_title)
            throw new Error('Missing title element for messagebox.');
 
        if (!el_content)
            throw new Error('Missing content element for messagebox.');

        if (!el_posBtn)
            throw new Error('Missing positive button for messagebox.');
  
        if (!el_negBtn)
            throw new Error('Missing negative button for messagebox.');

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
    //
    // MODAL USAGE
    //
    showInfo(message, options)
    {
        if (!this.checkElementsExists)
            throw new Error('Modal is not initialized properly');
        
        this.bindOptions(options);

        if (!options || !options.hasOwnProperty(this.PROP_KEY_MODAL_TITLE))
            $(this.modalTitle).text('Information');

        $(this.modalBody).html(message);
        
        this.updateClass(this.CLASS_MSG_INFO);

        this.modalInstance.show();
    }

    showWarning(message, options)
    {
        if (!this.checkElementsExists)
            throw new Error('Modal is not initialized properly');

        this.bindOptions(options);

        if (!options || !options.hasOwnProperty(this.PROP_KEY_MODAL_TITLE))
            $(this.modalTitle).text('Warning');
    
        $(this.modalBody).html(message);
        
        this.updateClass(this.CLASS_MSG_WARN);
    
        this.modalInstance.show();
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
}