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
use App\Models\TertiaryAttendance;
use App\Models\TertiaryStudent;
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

    const STATUS_VAL_TIMED_IN       = 'in';
    const STATUS_VAL_TIMED_OUT      = 'out';

    const MODE_DAILY    = 'daily';
    const MODE_WEEKLY   = 'weekly';
    const MODE_MONTHLY  = 'monthly';

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
    
    public $QUERY_MAP = [];

    public function getAttendance($options = array(), $studentType)
    {   
        $query = $this->getAttendanceBase($studentType);            // Build the base query
        
        if ($options['sort'] == 'oldest') 
            $query->orderBy('a.created_at', 'asc');     // Query for the last added row
        else
            $query->orderBy('a.created_at', 'desc');    // Query for the last added row

        switch ($options['mode']) 
        {
            case Attendance::MODE_DAILY:
                $query->whereDate('a.created_at', '=', Carbon::today());
                break;

            case Attendance::MODE_WEEKLY:
                $query->where(Attendance::FIELD_WEEK_NO, Carbon::now()->weekOfYear);
                break;

            case Attendance::MODE_MONTHLY:
                $query->whereMonth('a.created_at', date('m'))
                      ->whereYear('a.created_at',  date('Y'));
                break;
        }
        //
        // get only records every 30 rows. Then simulate a backend pagination
        // using ?page=1 | 2 | 3 | etc
        // return $this->beautifyDataset( $query->paginate(30), $options['mode'] );
        //
        return $this->beautifyDataset( $query->get(), $options['mode'] );
    }

    public function getAttendanceBase($studentLevel)
    {
        $fields = self::COMMON_FIELDS;                      // These fields are always selected

        switch ($studentLevel)
        {
            case Student::STUDENT_LEVEL_COLLEGE: 
                $fields[] = 's.year';
                $fields[] = 'c.course';
                break;

            case Student::STUDENT_LEVEL_SENIORS:
                $fields[] = 's.grade_level';
                $fields[] = 't.strand';
                break;
    
            case Student::STUDENT_LEVEL_ELEMENTARY: 
            case Student::STUDENT_LEVEL_JUNIORS:
                $fields[] = 's.grade_level';
                break;
        }

        $query = $this->getQueryMap()[$studentLevel];
 
        return $query->select($fields);
    }

    private function beautifyDataset($dataset, $mode = Attendance::MODE_DAILY) //, $studentLevel)
    {
        for ($i = 0; $i < $dataset->count(); $i++) 
        {
            $row = $dataset[$i];

            $row->id = encrypt($row->id);                   // Encrypt attendance record id

            // Fix the name as one fullname
            $row->name = implode(" ", [$row->lastname . ",", $row->firstname, $row->middlename]);

            //$this->bindExtraData($row, $studentLevel);      // Add extra data to the row

            $_timeIn        = '';       // Store original time in here before we format it
            $row->duration  = '';       // Store the formatted time duration here, only if there is timeout

            if ($row->time_in)
            {
                $_timeIn      = $row->time_in;
                $row->time_in = date('g:i A', strtotime($row->time_in));
            }

            if ($row->time_out)
            {
                $row->duration = Utils::getTimeDuration($_timeIn, $row->time_out);
                $row->time_out = date('g:i A', strtotime($row->time_out));
            }
            
            // Only today's records will have status badges.
            // Older records will have the 'Date created' field.
            if ($mode != Attendance::MODE_DAILY)
                $row->created_at   = date('D., M d', strtotime($row->created_at));
            
            else
                $row->statusBadge  = $this->makeStatusBadge($row->status);
            
            $dataset[$i] = $row;                            // Update the current row
        }

        return $dataset;
    }

    // 
    //     $query = DB::table( "$table as a" )->where(self::FIELD_CREATED_AT, '=', Carbon::today());

    public function makeQuery_ElemAttendance()
    {
        $attendance_table = ElemAttendance::getTableName();
        $students_table   = ElementaryStudent::getTableName();

        $query = DB::table( "$attendance_table as a" )
            ->leftJoin("$students_table as s", 's.id', '=', 'a.student_fk_id');

        return $query;
    }
    
    private function makeQuery_JHSAttendance()
    {
        $attendance_table = JuniorsAttendance::getTableName();
        $students_table   = JuniorStudent::getTableName();

        $query = DB::table( "$attendance_table as a" )
            ->leftJoin("$students_table as s", 's.id', '=', 'a.student_fk_id');

        return $query;
    }

    private function makeQuery_SHSAttendance()
    {
        $attendance_table = SeniorsAttendance::getTableName();
        $students_table   = SeniorStudent::getTableName();
        $strands          = Strand::getTableName();

        $query = DB::table( "$attendance_table as a" )
            ->leftJoin("$students_table as s", 's.id', '=', 'a.student_fk_id')
            ->leftJoin("$strands as t", 't.id', '=', 's.strand_id');

        return $query;
    }

    private function makeQuery_CollegeAttendance()
    {
        $attendance_table = TertiaryAttendance::getTableName();
        $students_table   = TertiaryStudent::getTableName();
        $courses          = Courses::getTableName();

        $query = DB::table( "$attendance_table as a" )
            ->leftJoin("$students_table as s", 's.id', '=', 'a.student_fk_id')
            ->leftJoin("$courses as c", 'c.id', '=', 's.course_id');

        return $query;
    }

    private function getQueryMap()
    {
        if (empty($this->QUERY_MAP)) 
        {
            $this->QUERY_MAP =
            [
                Student::STUDENT_LEVEL_ELEMENTARY  => $this->makeQuery_ElemAttendance(),
                Student::STUDENT_LEVEL_JUNIORS     => $this->makeQuery_JHSAttendance(),
                Student::STUDENT_LEVEL_SENIORS     => $this->makeQuery_SHSAttendance(),
                Student::STUDENT_LEVEL_COLLEGE     => $this->makeQuery_CollegeAttendance()
            ];
        }

        return $this->QUERY_MAP;
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
}