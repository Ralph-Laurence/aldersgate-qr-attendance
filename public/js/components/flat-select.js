export class FlatSelect
{
    constructor(mdbDropdownDom)
    { 
        this.domElement     = $(mdbDropdownDom).get(0);
        this.control        = new mdb.Dropdown(this.domElement);

        this.$dropdownRoot  = $(this.domElement).closest('.dropdown');
        this.valueField     = this.$dropdownRoot.find(`${mdbDropdownDom}-value`).get(0);
        this.toggleButton   = this.$dropdownRoot.find('.flat-select-button');
        
        this.toggleButtonDefaultText = 'Select'; //$(this.toggleButton).find('.button-text').text();

        $((e) => this.bindEvents()); 
    } 

    /**
     * In JavaScript, 'this' inside an event handler refers to the element that fired the event, 
     * not the instance of the class where the method is defined.
     * To solve this issue, we can use an arrow function which doesn’t have its own 'this', 
     * so it will refer to the 'this' of the enclosing context
     */
    bindEvents()
    {
        // $(this.domElement).parent().find('.dropdown-menu .dropdown-item').on('click', (event) =>
        this.$dropdownRoot.find('.dropdown-menu .dropdown-item').on('click', (event) =>
        { 
            console.warn('clicked item');

            this.resetSelectedItems();

            var itemVal = $(event.currentTarget).addClass('active').attr('data-item-value');

            if (this.ev_onItemClick && (typeof this.ev_onItemClick === 'function'))
                this.ev_onItemClick(itemVal);
  
            $(this.valueField).val(itemVal).trigger('input');
        });

        $(this.valueField).on('input', (event) => 
        { 
            var $this_input     = $(event.currentTarget);
            var this_input_val  = $this_input.val();
            var itemText        = $(this.domElement).parent().find('.dropdown-menu .dropdown-item.active').text();

            if (this_input_val)
                $this_input.closest('.flat-select').find('.error-label').text('');

            if (this.ev_onValueChanged && (typeof this.ev_onValueChanged === 'function'))
                this.ev_onValueChanged( this_input_val );

            $(this.toggleButton).find('.button-text').text(itemText);
        });
    }

    setValue(newValue)
    {
        $(this.valueField).val(newValue ); //.trigger('input');
        
        this.resetSelectedItems();

        var options = $(this.domElement).parent().find('.dropdown-menu .dropdown-item');
        var selected = 'Select';

        $.each(options, function()
        {
            var itemValue = $(this).data('item-value');
            
            if (newValue == itemValue)
            {
                $(this).addClass('active');
                selected = $(this).text() //itemValue;
            }
        });

        $(this.toggleButton).find('.button-text').text(selected);        
    }

    open()
    {
        this.control.show();
    }

    onItemClick(handler) {
        this.ev_onItemClick = handler;
    }

    onValueChanged(handler) {
        this.ev_onValueChanged = handler;
    }

    getValue() 
    {
        return $(this.valueField).val();
    }

    reset()
    {
        $(this.valueField).val('')
        $(this.toggleButton).find('.button-text').text(this.toggleButtonDefaultText);

        this.resetSelectedItems();
    }

    resetSelectedItems()
    {
        $(this.domElement).parent().find('.dropdown-menu .dropdown-item').removeClass('active');
    }
}