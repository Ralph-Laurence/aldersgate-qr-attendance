<?php

use App\Http\Controllers\AttendanceHomeController;
use App\Http\Controllers\CollegeAttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ElemAttendanceController;
use App\Http\Controllers\ElemStudentsController;
use App\Http\Controllers\JuniorsAttendanceController;
use App\Http\Controllers\JuniorStudentsController;
use App\Http\Controllers\LibrariansController;
use App\Http\Controllers\MasterUsersController;
use App\Http\Controllers\ModeratorsController;
use App\Http\Controllers\QRScannerController;
use App\Http\Controllers\SeniorsAttendanceController;
use App\Http\Controllers\SeniorStudentsController;
use App\Http\Controllers\CollegeStudentsController;
use App\Http\Extensions\Routes;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('/');

Route::controller(QRScannerController::class)->group(function()
{
    Route::get('/qr-attendance', 'index')->name('qr-scanner');
    Route::post('/qr-attendance/scan-result', 'update')->name('qr-scan-result');
});
 
Route::controller(DashboardController::class)->group(function()
{
    Route::get('/backoffice/dashboard', 'index')->name( Routes::DASHBOARD['index'] );
});

//===================================================================
// .................... ATTENDANCE CONTROLLERS ......................
//===================================================================

Route::controller(AttendanceHomeController::class)->group(function()
{
    Route::get('/backoffice/attendance', 'index')->name( Routes::ATTENDANCE['index'] );
});

Route::controller(ElemAttendanceController::class)->group(function()
{
    Route::get('/backoffice/attendance/elementary/today/{sort?}',      'index'        )->name( Routes::ATTENDANCE_ELEM['index']);
    Route::get('/backoffice/attendance/elementary/this-week/{sort?}',  'showWeekly'   )->name( Routes::ATTENDANCE_ELEM['weekly'] );
    Route::get('/backoffice/attendance/elementary/this-month/{sort?}', 'showMonthly'  )->name( Routes::ATTENDANCE_ELEM['monthly'] );
    //Route::get('/backoffice/attendance/elementary/{mode}/{sort?}',   'index'  )->name( Routes::ATTENDANCE_ELEM['index'] );
});

Route::controller(JuniorsAttendanceController::class)->group(function()
{
    Route::get('/backoffice/attendance/juniors/today/{sort?}',      'index'      )->name( Routes::ATTENDANCE_JUNIORS['index'] );
    Route::get('/backoffice/attendance/juniors/this-week/{sort?}',  'showWeekly' )->name( Routes::ATTENDANCE_JUNIORS['weekly'] );
    Route::get('/backoffice/attendance/juniors/this-month/{sort?}', 'showMonthly')->name( Routes::ATTENDANCE_JUNIORS['monthly'] );
});

Route::controller(SeniorsAttendanceController::class)->group(function()
{
    Route::get('/backoffice/attendance/seniors/today/{sort?}',      'index'      )->name( Routes::ATTENDANCE_SENIORS['index'] );
    Route::get('/backoffice/attendance/seniors/this-week/{sort?}',  'showWeekly' )->name( Routes::ATTENDANCE_SENIORS['weekly'] );
    Route::get('/backoffice/attendance/seniors/this-month/{sort?}', 'showMonthly')->name( Routes::ATTENDANCE_SENIORS['monthly'] );
});

Route::controller(CollegeAttendanceController::class)->group(function()
{
    Route::get('/backoffice/attendance/college/{sort?}',            'index'  )->name( Routes::ATTENDANCE_COLLEGE['index'] );
    Route::get('/backoffice/attendance/college/this-week/{sort?}',  'weekly' )->name( Routes::ATTENDANCE_COLLEGE['weekly'] );
    Route::get('/backoffice/attendance/college/this-month/{sort?}', 'monthly')->name( Routes::ATTENDANCE_COLLEGE['monthly'] );
});

//===================================================================
// ...................... USERS CONTROLLERS .........................
//===================================================================

Route::controller(LibrariansController::class)->group(function()
{
    Route::get('/backoffice/users/librarian/{sort?}',  'index'   )->name( Routes::LIBRARIANS['index']   );
    Route::post('/backoffice/users/librarian/add',     'store'   )->name( Routes::LIBRARIANS['store']   );
    Route::post('/backoffice/users/librarian/edit',    'update'  )->name( Routes::LIBRARIANS['update']  );
    Route::post('/backoffice/users/librarian/destroy', 'destroy' )->name( Routes::LIBRARIANS['destroy'] );
});

Route::controller(ModeratorsController::class)->group(function()
{
    Route::get('/backoffice/users/moderator/{sort?}',  'index'   )->name( Routes::MODERATORS['index']   );
    Route::post('/backoffice/users/moderator/add',     'store'   )->name( Routes::MODERATORS['store']   );
    Route::post('/backoffice/users/moderator/edit',    'update'  )->name( Routes::MODERATORS['update']  );
    Route::post('/backoffice/users/moderator/destroy', 'destroy' )->name( Routes::MODERATORS['destroy'] );
});

Route::controller(MasterUsersController::class)->group(function()
{
    Route::get('/backoffice/users/master/{sort?}',   'index'   )->name( Routes::MASTER_USERS['index']   );
    Route::post('/backoffice/users/master/add',      'store'   )->name( Routes::MASTER_USERS['store']   );
    Route::post('/backoffice/users/master/edit',     'update'  )->name( Routes::MASTER_USERS['update']  );
    Route::post('/backoffice/users/master/destroy',  'destroy' )->name( Routes::MASTER_USERS['destroy'] );
});

//===================================================================
// ...................... USERS CONTROLLERS .........................
//===================================================================

Route::controller(ElemStudentsController::class)->group(function()
{
    Route::get( '/backoffice/students/elementary/{sort?}', 'index'  )->name( Routes::ELEM_STUDENT['index']   );
    Route::post('/backoffice/students/elementary/add',     'store'  )->name( Routes::ELEM_STUDENT['store']   );
    Route::post('/backoffice/students/elementary/edit',    'update' )->name( Routes::ELEM_STUDENT['update']  );
    Route::post('/backoffice/students/elementary/delete',  'destroy')->name( Routes::ELEM_STUDENT['destroy'] );
});

Route::controller(JuniorStudentsController::class)->group(function()
{
    Route::get( '/backoffice/students/juniors/{sort?}',   'index'  )->name( Routes::JUNIOR_STUDENT['index']   );
    Route::post('/backoffice/students/juniors/add',       'store'  )->name( Routes::JUNIOR_STUDENT['store']   );
    Route::post('/backoffice/students/juniors/edit',      'update' )->name( Routes::JUNIOR_STUDENT['update']  );
    Route::post('/backoffice/students/juniors/delete',    'destroy')->name( Routes::JUNIOR_STUDENT['destroy'] );
});

Route::controller(SeniorStudentsController::class)->group(function()
{
    Route::get( '/backoffice/students/seniors/{sort?}',   'index'  )->name( Routes::SENIOR_STUDENT['index']   );
    Route::post('/backoffice/students/seniors/add',       'store'  )->name( Routes::SENIOR_STUDENT['store']   );
    Route::post('/backoffice/students/seniors/edit',      'update' )->name( Routes::SENIOR_STUDENT['update']  );
    Route::post('/backoffice/students/seniors/delete',    'destroy')->name( Routes::SENIOR_STUDENT['destroy'] );
});

Route::controller(CollegeStudentsController::class)->group(function()
{ 
    Route::get( '/backoffice/students/college/{sort?}', 'index'  )->name( Routes::COLLEGE_STUDENT['index']   );
    Route::post('/backoffice/students/college/add',     'store'  )->name( Routes::COLLEGE_STUDENT['store']   );
    Route::post('/backoffice/students/college/edit',    'update' )->name( Routes::COLLEGE_STUDENT['update']  );
    Route::post('/backoffice/students/college/delete',  'destroy')->name( Routes::COLLEGE_STUDENT['destroy'] );
});

Route::get('/test', function() {
    return view('testground');
});