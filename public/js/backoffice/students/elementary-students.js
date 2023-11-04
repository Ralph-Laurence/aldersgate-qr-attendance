$(document).ready(function()
{
    const gradeLevelSelect = new FlatSelect('#input-grade-level');

    $(document)
    .on('onPopulateExtra', (event, out) => 
    {
        if ('gradeLevel' in out)
            gradeLevelSelect.setValue(out.gradeLevel);
    })
    .on('onCleanupForm', () => gradeLevelSelect.reset());
});
