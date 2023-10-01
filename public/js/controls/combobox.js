class ComboBox
{ 
    constructor(elementClass) 
    {
        this.domElement = $(elementClass);

        this.control = this.domElement.selectmenu();
        this.__mainInstance = this.control.selectmenu('instance');
        this.__onSelectEvent = null;

        this.control.on("selectmenuselect", (event, ui) =>
        { 
            if (this.__onSelectEvent)
                this.__onSelectEvent(event, ui); 
        }); 
    }

    OnSelect(eventHandler) {
        this.__onSelectEvent = eventHandler;
    }

    GetInstance(){
        return this.__mainInstance;
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

    AddItem(value, text, selected = false, disabled = false) 
    {
        var option = $('<option>', {
            value: value,
            text: text,
            selected: selected,
            disabled: disabled
         });

        this.domElement.append(option);
        this.Refresh();
    }
}