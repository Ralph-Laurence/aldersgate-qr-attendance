<?php

use App\Http\Controllers\QRScannerController;
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
});

Route::controller(QRScannerController::class)->group(function()
{
    Route::get('/qr-attendance', 'index')->name('qr-scanner');
    Route::post('/qr-attendance/scan-result', 'update')->name('qr-scan-result');
});