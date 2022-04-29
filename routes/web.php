<?php
namespace App\Http\Controllers;
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
    return '<h1>(*^*)</h1>';
});

Route::prefix('cron')->name('cron.')->group(function(){

    /**
     * Posts
     */
    Route::get('publish-scheduled', [CronController::class, 'publishSchedulePost']);
    Route::get('delete-scheduled', [CronController::class, 'deleteSchedule']);

    Route::get('auto-upload1',[CronController::class,'autoUploadS3'])->name('auto.s3');
    Route::get('files',[CronController::class,'getFilesS3'])->name('files');
});