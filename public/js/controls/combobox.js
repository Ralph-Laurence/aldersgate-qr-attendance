/**
 * This is a helper class that wraps the JQuery-UI's selectmenu
 */
class ComboBox
{ 
    constructor(elementClass, disabledOnLoad = false) 
    {
        this.domElement = $(elementClass);

        // Begin: Main instance
        //
        this.control = this.domElement.selectmenu({
            disabled: disabledOnLoad
        });

        this.__mainInstance = this.control.selectmenu('instance');
        //
        // End: Main instance

        // Begin: Event handling
        //
        this.__onSelectEvent = null;

        this.control.on("selectmenuselect", (event, ui) => { 
            if (this.__onSelectEvent)
                this.__onSelectEvent(event, ui); 
        });
        //
        // End: Event handling
    }

    GetInstance(){
        return this.__mainInstance;
    }

    /**
     * @property {function} GetSelectedItem Gets the selected value from <option>'s "value" attribute.
     * @returns {string}
     */
    GetSelectedItem()
    {
        var value = this.domElement.find('option:selected').attr('value');
        return value;
    }

    Enable() {
        this.__mainInstance.enable();
    }

    Disable() {
        this.__mainInstance.disable();
    } 

    Refresh() {
        this.__mainInstance.refresh();
    }

    /**
     * @property {function} AddItem inserts a new <option> item into the <select> DOM element
     * @param {string} value the data value of <option>
     * @param {string} text the text content of <option>
     * @param {boolean} selected should we select the <option> after adding?
     * @param {boolean} disabled should we disable the <option> after adding?
     */
    AddItem(value, text, selected = false, disabled = false) 
    {
        var option = $('<option>', {
            value: value,
            text: text,
            selected: selected,
            disabled: disabled
         });

        this.domElement.append(option);
    }

    /**
     * @property {function} AppendItem inserts a new <option> item into the <select> DOM element then refreshes the selectmenu after.
     * @param {string} value the data value of <option>
     * @param {string} text the text content of <option>
     * @param {boolean} selected should we select the <option> after adding?
     * @param {boolean} disabled should we disable the <option> after adding?
     */
    AppendOption(value, text, selected = false, disabled = false) 
    {
        this.AddItem(value, text, selected, disabled);
        this.Refresh();
    }

    /**
     * @property {function} ClearItems empties the DOM <select> options then refreshes the selectmenu
     */
    ClearItems()
    {
        this.domElement.empty();
        this.Refresh();
    }

    /**
     * @property {function} OnSelect event callback when an item was selected from the selectmenu
     * @param {function} eventHandler the callback function
     */
    OnSelect(eventHandler) {
        this.__onSelectEvent = eventHandler;
    }
}