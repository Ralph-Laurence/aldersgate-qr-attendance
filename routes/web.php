<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ElemStudentsController;
use App\Http\Controllers\JuniorStudentsController;
use App\Http\Controllers\LibrariansController;
use App\Http\Controllers\MasterUsersController;
use App\Http\Controllers\ModeratorsController;
use App\Http\Controllers\QRScannerController;
use App\Http\Controllers\SeniorStudentsController;
use App\Http\Controllers\TertiaryStudentsController;
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

Route::controller(AttendanceController::class)->group(function()
{
    Route::get('/backoffice/attendance', 'index')->name( Routes::ATTENDANCE['index'] );
});

Route::controller(LibrariansController::class)->group(function()
{
    Route::get('/backoffice/users/librarian/{sort?}', 'index')->name( Routes::LIBRARIANS['index'] );
});

Route::controller(ModeratorsController::class)->group(function()
{
    Route::get('/backoffice/users/moderator/{sort?}', 'index')->name( Routes::MODERATORS['index'] );
});

Route::controller(MasterUsersController::class)->group(function()
{
    Route::get('/backoffice/users/master/{sort?}', 'index')->name( Routes::MASTER_USERS['index'] );
});

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

Route::controller(TertiaryStudentsController::class)->group(function()
{ 
    Route::get( '/backoffice/students/college/{sort?}', 'index'  )->name( Routes::COLLEGE_STUDENT['index']   );
    Route::post('/backoffice/students/college/add',     'store'  )->name( Routes::COLLEGE_STUDENT['store']   );
    Route::post('/backoffice/students/college/edit',    'update' )->name( Routes::COLLEGE_STUDENT['update']  );
    Route::post('/backoffice/students/college/delete',  'destroy')->name( Routes::COLLEGE_STUDENT['destroy'] );
});

Route::get('/test', function() {
    return view('testground');
});