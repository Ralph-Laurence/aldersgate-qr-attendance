<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QRScannerController;
use App\Http\Controllers\StudentsController;
use App\Http\Extensions\RouteNames;
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
    Route::get('/backoffice/dashboard', 'index')->name( RouteNames::DASHBOARD );
});

Route::controller(StudentsController::class)->group(function()
{
    Route::get('/backoffice/students', 'index')->name( RouteNames::STUDENTS );
});

Route::controller(AttendanceController::class)->group(function()
{
    Route::get('/backoffice/attendance', 'index')->name( RouteNames::ATTENDANCE );
});
