export class FlatPagerLength
{
    constructor(dataTable)
    {
        this.$control = $(`${dataTable}`).parent().find('.dataTables_length');
    }

    applyTo(dropdown)
    {
        var $select = this.$control.find('select');
        $select.hide();

        // Find the pagination length options, iterate thru each of them,
        // then copy their values and append them onto the dropdowns
        var options = this.$control.find('option');
        var selectedOptionText = $(options[0]).attr('value');

        $.each(options, function()
        {
            const val = $(this).attr('value');
            var isSelected = '';

            if (val == $select.val())
            {
                isSelected = 'selected';
                selectedOptionText = val;
            }

            $(dropdown).find('ul.dropdown-menu').append(`<li><a class="dropdown-item page-length-item ${isSelected}"
                data-page-length="${val}">${val}</a></li>`);
        });

        $(dropdown).find('.btn-page-length').text(selectedOptionText);

        this.__bindSelection(dropdown, $select);
    }

    __bindSelection(dropdown, select)
    {
        $(dropdown).find(".dropdown-item.page-length-item").on('click', (e) =>
        {
            var $that = $(e.currentTarget);
            
            var length = $that.data('page-length');
            var toggleButton = $(dropdown).find('.btn-page-length');

            this.changePageLength($that, length, toggleButton, select);
        });
    }

    changePageLength(anchor, length, toggleButton, select) 
    {
        $(select).val(length).change();

        $(toggleButton).text(length);

        $(anchor).closest('ul').find('li > a').removeClass('selected');
        $(anchor).addClass('selected');
    }
}