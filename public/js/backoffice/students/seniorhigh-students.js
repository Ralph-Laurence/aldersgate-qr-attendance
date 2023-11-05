$(document).ready(function()
{
    const strandSelect      = new FlatSelect('#input-strand');
    const gradeLevelSelect  = new FlatSelect('#input-grade-level');

    $(document)
    .on('onPopulateExtra', (event, out) => 
    { 
        if ('strand' in out)
            strandSelect.setValue(out.strand);

        if ('gradeLevel' in out)
            gradeLevelSelect.setValue(out.gradeLevel);
    })
    .on('onCleanupForm', () => 
    {
        strandSelect.reset();
        gradeLevelSelect.reset();
    });
});
