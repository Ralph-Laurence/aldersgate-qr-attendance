$(document).ready(function()
{
    const courseSelect = new FlatSelect('#input-course');
    const yearSelect   = new FlatSelect('#input-year-level');

    $(document)
    .on('onPopulateExtra', (event, out) => 
    { 
        if ('course' in out)
            courseSelect.setValue(out.course);

        if ('yearlevel' in out)
            yearSelect.setValue(out.yearlevel);
    })
    .on('onCleanupForm', () => 
    {
        courseSelect.reset();
        yearSelect.reset();
    });
});
