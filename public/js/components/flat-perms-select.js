export class FlatPermsSelect
{
    constructor(elementId)
    {
        this.$mainControl       = $(elementId);//.find('.select-value');
        this.$parentContainer   = this.$mainControl.closest('.permission-select');

        if (this.$mainControl.length < 1 || this.$parentContainer.length < 1)
            throw new Error(`Permission selector control failed to initialize. Make sure that there is an element with id or class name "${elementId}".`);

        $(() => this.bindEvents());
    }

    bindEvents()
    {
        this.$mainControl.closest('.permission-select').find('.btn').on('click', (e) => 
        {
            var value = $(e.currentTarget).data('item-value');

            this.$mainControl.val(value).trigger('input');

            console.warn('selected -> ' + value);
        });
    }

    getValue()
    {
        return this.$mainControl.val();
    }

    setValue(value)
    {
        this.$mainControl.val(value);//.trigger('input');
        this.redraw();
    }

    reset()
    {
        this.setValue('', true);
    }
    
    redraw()
    {
        var value = this.getValue();

        // redraw | -> remove the checked appearance
        this.$parentContainer.find('.btn-check').prop('checked', false);

        // redraw | -> set checked appearance
        var $labels = this.$parentContainer.find('.btn');

        $.each($labels, (i, label) => 
        {
            var hasDefaultValue = (value !== '' && value !== undefined && value == $(label).data('item-value'));

            // default value for disabled-checked option
            var $hiddenDefault = $(this.$parentContainer).find('.default-option-value');

            if (hasDefaultValue || $hiddenDefault.length > 0)
                $(label).prev('.btn-check').prop('checked', true);
        });
    }
}

// TASK TOMORROW :
//fix validation of username of the same user