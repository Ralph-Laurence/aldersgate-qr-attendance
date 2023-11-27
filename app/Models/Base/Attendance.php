<?php

namespace App\Models\Base;

use App\Http\Extensions\Utils;
use App\Models\Courses;
use App\Models\ElemAttendance;
use App\Models\ElementaryStudent;
use App\Models\JuniorsAttendance;
use App\Models\JuniorStudent;
use App\Models\SeniorsAttendance;
use App\Models\SeniorStudent;
use App\Models\Strand;
use App\Models\CollegeAttendance;
use App\Models\CollegeStudent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Str;

class Attendance extends Model
{
    use HasFactory;

    const FIELD_ID                  = 'id';
    const FIELD_STUDENT_FK          = 'student_fk_id';
    const FIELD_TIME_IN             = 'time_in';
    const FIELD_TIME_OUT            = 'time_out';
    const FIELD_STAY_DURATION       = 'stay_duration';
    const FIELD_WEEK_NO             = 'week_no';
    const FIELD_STATUS              = 'status';
    const FIELD_UPDATED_BY          = 'updated_by';
    const FIELD_CREATED_AT          = 'created_at';
    const FIELD_UPDATED_AT          = 'updated_at';
    const FIELD_RECORDED_AT         = 'recorded_at';

    const STATUS_VAL_TIMED_IN       = 'in';
    const STATUS_VAL_TIMED_OUT      = 'out';

    const MODE_DAILY                = 'daily';
    const MODE_WEEKLY               = 'weekly';
    const MODE_MONTHLY              = 'monthly';

    protected $guarded = 
    [
        self::FIELD_ID
    ];
 
    const COMMON_FIELDS = 
    [
        'a.' . self::FIELD_ID,
        'a.' . self::FIELD_TIME_IN,
        'a.' . self::FIELD_TIME_OUT,
        'a.' . self::FIELD_STATUS,
        'a.' . self::FIELD_CREATED_AT,

        's.' . Student::FIELD_STUDENT_NUM, 
        's.' . Student::FIELD_FNAME,
        's.' . Student::FIELD_MNAME,
        's.' . Student::FIELD_LNAME,
    ];

    public const StatusBadges =
    [
        self::STATUS_VAL_TIMED_IN  => [ 'type' => 'status-badge-success', 'icon' => 'fa-check'  ],
        self::STATUS_VAL_TIMED_OUT => [ 'type' => 'status-badge-dark',    'icon' => 'fa-times'  ],
    ];

    public function getAttendance($options = array(), $studentLevel)
    {   
        $queryBuilder = 
        [
            Student::STUDENT_LEVEL_COLLEGE      => $this->makeQuery_CollegeAttendance(),
            Student::STUDENT_LEVEL_SENIORS      => $this->makeQuery_SHSAttendance(),
            Student::STUDENT_LEVEL_JUNIORS      => $this->makeQuery_JHSAttendance(),
            Student::STUDENT_LEVEL_ELEMENTARY   => $this->makeQuery_ElemAttendance(),
        ];

        $attendanceModes = 
        [
            Attendance::MODE_DAILY  => function ($query) 
            { 
                $query->whereDate('a.created_at', '=', Carbon::today()); 
            },
            Attendance::MODE_WEEKLY => function ($query) 
            { 
                $query->where(Attendance::FIELD_WEEK_NO, Carbon::now()->weekOfYear); 
            },
            Attendance::MODE_MONTHLY => function ($query) 
            {
                $query->whereMonth('a.created_at', date('m'))
                      ->whereYear('a.created_at',  date('Y'));
            }
        ];

        $query = $queryBuilder[$studentLevel];        // Build the query for target student level
        $attendanceModes[$options['mode']]($query);   // Set attendance to retrieve by its mode
        
        $sortMode = 'desc';
        
        if (isset($options['sort']) && $options['sort'] == 'oldest') 
            $sortMode = 'asc';

        $query->orderBy('a.created_at', $sortMode);

        return $this->beautifyDataset( $query->get(), $options['mode'], $studentLevel);
    }

    private function beautifyDataset($dataset, $mode = Attendance::MODE_DAILY, $studentLevel)
    {
        foreach ($dataset as $row) 
        {
            $row->id   = encrypt($row->id);                 // Encrypt attendance record id
            $row->name = $this->implodeNames($row);         // Fix the name as one fullname
            $row->duration  = '';                           // Store the formatted time duration here, only if there is timeout

            if ($row->time_out) 
            {
                $row->duration = Utils::getTimeDuration($row->time_in, $row->time_out);
                $row->time_out = Utils::timeToString($row->time_out);
            }

            $row->time_in = Utils::timeToString($row->time_in);

            // Only today's records will have status badges.
            // Older records will have the 'Date created' field.
            if ($mode != Attendance::MODE_DAILY)
                $row->created_at   = Utils::dateToString($row->created_at, 'D., M d');

            else
                $row->statusBadge  = $this->makeStatusBadge($row->status);

            $this->bindExtraData($row, $studentLevel);          // Add extra data to the row
        }

        return $dataset;
    }

    public function makeQuery_ElemAttendance()
    {
        $attendance_table = ElemAttendance::getTableName();
        $students_table   = ElementaryStudent::getTableName();

        $fields   = self::COMMON_FIELDS;
        $fields[] = 's.grade_level';

        $query = DB::table( "$attendance_table as a" )
            ->leftJoin("$students_table as s", 's.id', '=', 'a.student_fk_id')
            ->select($fields);

        return $query;
    }
    
    private function makeQuery_JHSAttendance()
    {
        $attendance_table = JuniorsAttendance::getTableName();
        $students_table   = JuniorStudent::getTableName();

        $fields   = self::COMMON_FIELDS;
        $fields[] = 's.grade_level';

        $query = DB::table( "$attendance_table as a" )
            ->leftJoin("$students_table as s", 's.id', '=', 'a.student_fk_id')
            ->select($fields);

        return $query;
    }

    private function makeQuery_SHSAttendance()
    {
        $attendance_table = SeniorsAttendance::getTableName();
        $students_table   = SeniorStudent::getTableName();
        $strands          = Strand::getTableName();

        $fields   = self::COMMON_FIELDS;
        $fields[] = 's.grade_level'; 
        $fields[] = 't.strand';

        $query = DB::table( "$attendance_table as a" )
            ->leftJoin("$students_table as s", 's.id', '=', 'a.student_fk_id')
            ->leftJoin("$strands as t", 't.id', '=', 's.strand_id')
            ->select($fields);

        return $query;
    }

    private function makeQuery_CollegeAttendance()
    {
        $attendance_table = CollegeAttendance::getTableName();
        $students_table   = CollegeStudent::getTableName();
        $courses          = Courses::getTableName();

        $fields   = self::COMMON_FIELDS;
        $fields[] = 's.year'; 
        $fields[] = 'c.course';

        $query = DB::table( "$attendance_table as a" )
            ->leftJoin("$students_table as s", 's.id', '=', 'a.student_fk_id')
            ->leftJoin("$courses as c", 'c.id', '=', 's.course_id')
            ->select($fields);

        return $query;
    }

    private function makeStatusBadge($status)
    {
        if (!array_key_exists($status, self::StatusBadges))
        {
            return [ 
                'type'  => 'badge-warning', 
                'icon'  => 'fa-triangle-exclamation',
                'label' => 'Unknown'
            ];
        }
        
        return self::StatusBadges[$status] + ['label' => Str::ucfirst($status)];
    }
    //
    // Create a JSON object that will be used later to
    // autofill the form during Edit mode
    //
    private function bindExtraData($row, $studentLevel) : void
    {
        $initialRowData = [
            'studentNo' => $row->student_no,
            'name'      => $this->implodeNames($row),
            'timeIn'    => $row->time_in,
            'timeOut'   => $row->time_out,
            'status'    => $row->status
        ];

        $extraData = 
        [
            Student::STUDENT_LEVEL_ELEMENTARY => fn ($row) => ['gradeLevel' => $row->grade_level],
            Student::STUDENT_LEVEL_JUNIORS    => fn ($row) => ['gradeLevel' => $row->grade_level],
            Student::STUDENT_LEVEL_SENIORS    => fn ($row) => 
            [
                'strand'     => $row->strand,
                'gradeLevel' => $row->grade_level
            ],
            Student::STUDENT_LEVEL_COLLEGE    => function ($row) 
            {
                // Convert the year levels to their ordinal equivalent
                // but only return the ordinal suffix
                $row->year_ordinal = Utils::toOrdinal($row->year, true);

                return [
                    'course'    => $row->course,
                    'yearlevel' => $row->year
                ];
            },
        ];

        if (isset($extraData[$studentLevel])) 
        {
            $rowData        = $initialRowData + $extraData[$studentLevel]($row);
            $row->rowData   = json_encode($rowData);
        }
    }

    private function implodeNames($row)
    {
        return implode(" ", [$row->lastname . ",", $row->firstname, $row->middlename]);
    }
}