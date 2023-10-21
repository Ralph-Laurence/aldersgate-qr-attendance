$(function()
{
    $('.flat-input .input-text .main-control[required]').on('input', (e) => 
    { 
        var $target = $(e.currentTarget);

        if ( $target.val() )
        {
            $target.parent().removeClass('has-error');
            $target.closest('.flat-input').find('.error-label').text('');
        }
        else
        {
            $target.parent().addClass('has-error');

            var $placeholder = $target.closest('.flat-input').find('.main-control').attr('placeholder');

            $target.closest('.flat-input')
                .find('.error-label')
                .text((!$placeholder) ? 'Please fill out this field' : `${$placeholder} must be filled out`);
        }
    });
});