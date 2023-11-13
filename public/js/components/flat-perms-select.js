export class FlatPermsSelect
{
    constructor(elementId)
    {
        this.$mainControl     = $(elementId);
        this.$parentContainer = this.$mainControl.closest('.permission-select');
        this.defaultValue     = this.$mainControl.data('default');

        if (this.$mainControl.length < 1 || this.$parentContainer.length < 1)
            throw new Error(`Permission selector control failed to initialize. Make sure that there is an element with id or class name "${elementId}".`);

        $(() => this.bindEvents());
    }

    bindEvents()
    {
        this.$mainControl.closest('.permission-select').find('.btn').on('click', (e) => 
        {
            var value = $(e.currentTarget).data('item-value');

            this.$mainControl.val(value);

            console.warn('selected -> ' + value);
        });
    }

    getValue()
    {
        return this.$mainControl.val();
    }

    setValue(value, redraw)
    {
        // optional bool must be undefined because when redraw is false, 
        // it returns true because false is a falsy value
        redraw = redraw === undefined ? true : redraw;

        this.$mainControl.val(value);

        if (typeof redraw === 'boolean' && redraw !== false)
            this.redraw();
    }

    reset()
    {
        this.setValue('', true);
    }
    
    redraw()
    {
        var value = this.defaultValue;

        // redraw | -> remove the checked appearance
        this.$parentContainer.find('.btn-check').prop('checked', false);

        // redraw | -> set checked appearance
        var $labels = this.$parentContainer.find('.btn');

        $.each($labels, (i, label) => 
        {
            if (value !== '' && value !== undefined && value == $(label).data('item-value'))
                $(label).prev('.btn-check').prop('checked', true);
        });
    }
}