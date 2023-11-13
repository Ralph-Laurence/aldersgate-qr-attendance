$(document).ready(function()
{
    // Allow input texts to accept only positive non-zero integer
    $(".abs-int")
    .on("input", function() 
    {
        var nonNumReg = /[^0-9]/g;
        $(this).val($(this).val().replace(nonNumReg, ''));
    })
    .on('blur', function()
    {
        if ($(this).val())
            return;

        $(this).val('1');
    });
});

function generateRandomString(length, prefix) 
{
    prefix = prefix || '';

    // Prefix the resulting string with underscore
    var result           = prefix; 
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}