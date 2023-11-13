import { FlatPermsSelect } from "../../components/flat-perms-select.js";

$(document).ready(function () 
{
    /*window.*/ const permStudents     = new FlatPermsSelect('.input-students-perm');
    /*window.*/ const attendancePerms  = new FlatPermsSelect('.input-attendance-perm');
    /*window.*/ const userPerms        = new FlatPermsSelect('.input-users-perm');
    /*window.*/ const advancePerms     = new FlatPermsSelect('.input-advanced-perm');

    $(document).on('onCleanupForm', () => 
    {
        permStudents   .reset();
        attendancePerms.reset();
        userPerms      .reset();
        advancePerms   .reset();
    });
});

