<?php
namespace Modules\Super\Http\Controllers;

use Illuminate\Http\Request;
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

Route::prefix('super')->name('super.')->group(function() {
    Route::get('/login', [UsersController::class, 'login'])->name('login');
    Route::post('/verify', [UsersController::class, 'verify'])->name('verify');

    Route::middleware('super')->group(function(){
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/logout', [UsersController::class, 'logout'])->name('logout');

        Route::name('users.')->group(function(){
            Route::get('/users', [UsersController::class, 'listUsers'])->name('fan');
            Route::get('/creators', [UsersController::class, 'listCreators'])->name('creator');
            Route::get('/creators-pro', [UsersController::class, 'listCreatorsPro'])->name('creator.pro');

            Route::get('view/{user}', [UsersController::class, 'viewUser'])->name('view');
        });
        // Route::get('/users', [UsersController::class, 'listUsers'])->name('users');
        // Route::get('/creators', [UsersController::class, 'listCreators'])->name('creators');
        Route::get('/creator-requests', [UsersController::class, 'listCreatorRequests'])->name('requests');
        Route::get('/creator-request/{creatorRequest}', [UsersController::class, 'creatorRequest'])->name('request');
        Route::post('/creator-request/{creatorRequest}/approve', [UsersController::class, 'creatorRequestApprove'])->name('approve');
        Route::post('/creator-request/{creatorRequest}/reject', [UsersController::class, 'creatorRequestReject'])->name('reject');
        Route::post('/creator-request/{creatorRequest}/incomplete', [UsersController::class, 'creatorRequestIncomplete'])->name('request.incomplete');
        
        Route::prefix('posts')->name('post.')->group(function(){
            Route::get('/', [PostController::class, 'index'])->name('list');
            Route::get('/reported', [PostController::class, 'reported'])->name('report');
            Route::get('/archived', [PostController::class, 'archive'])->name('archive');
            Route::get('/blocked', [PostController::class, 'blocked'])->name('block');
            Route::get('/view/{post}', [PostController::class, 'view'])->name('view');
            Route::get('/reported/{post}', [PostController::class, 'reportedPost'])->name('report.view');

            // Actions
            Route::get('/clear-reports/{post}', [PostController::class, 'clearReportsPost'])->name('report.clear');
            Route::get('/publish/{post}', [PostController::class, 'publishPost'])->name('publish');
            Route::get('/block/{post}', [PostController::class, 'blockPost'])->name('block.post');
            Route::get('/delete/{post}', [PostController::class, 'deletePost'])->name('delete');
            Route::get('/archive/{post}', [PostController::class, 'archivePost'])->name('archive.post');
            
        });
    });
});
