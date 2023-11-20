import { FlatPermsSelect } from "../../components/flat-perms-select.js";

$(document).ready(function () 
{
    /* const  */ window.studentPerms     = new FlatPermsSelect('#perm-students');
    /* const  */ window.attendancePerms  = new FlatPermsSelect('#perm-attendance');
    /* const  */ window.userPerms        = new FlatPermsSelect('#perm-users');
    /* const  */ window.advancePerms     = new FlatPermsSelect('#perm-advanced');

    $(document)
    .on('onPopulateExtra', (event, out) => 
    { 
        if ('perm_advanced' in out)
            advancePerms.setValue(out.perm_advanced);

        if ('perm_attendance' in out)
            attendancePerms.setValue(out.perm_attendance);

        if ('perm_students' in out)
            studentPerms.setValue(out.perm_students);

        if ('perm_users' in out)
            userPerms.setValue(out.perm_users);
    })
    .on('onCleanupForm', () => 
    {
        studentPerms   .reset();
        attendancePerms.reset();
        userPerms      .reset();
        advancePerms   .reset();
    });
});

