<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommiteeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::post('/register', [UserController::class, 'create'])->name('register_user');
Route::middleware(['auth'])->group(function(){
    Route::middleware(['admin'])->group(function(){
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::get('/archives', [SpaceController::class, 'showArchive'])->name('show_archives');
        Route::get('/get-committee/{id}', [CommiteeController::class, 'committeeUser'])->name('committe_user');
        Route::post('/store_space', [SpaceController::class, 'store'])->name('store_space');
        Route::post('/store-meeting', [MeetingController::class, 'store'])->name('store_meeting');
        Route::post('/update-meeting', [MeetingController::class, 'update'])->name('update_meeting');
        Route::post('/store_task', [TaskController::class, 'store'])->name('store_task');
        Route::post('/update-allTasks', [TaskController::class, 'update'])->name('update_all_tasks');
        Route::get('/delete-task/{id}', [TaskController::class, 'delete'])->name('delete_task');
        Route::get('/delete-space/{id}', [SpaceController::class, 'delete'])->name('delete_space');
        Route::get('/delete-meeting/{id}', [MeetingController::class, 'delete'])->name('delete_meeting');
        Route::get('/archive-space/{id}', [SpaceController::class, 'archive'])->name('archive_space');
        Route::get('/archive-meeting/{id}', [MeetingController::class, 'archive'])->name('archive_meeting');
        Route::get('/archive-task/{id}', [TaskController::class, 'archive'])->name('archive_task');
        Route::get('/restore-task/{id}', [TaskController::class, 'restore'])->name('restore_task');
        Route::get('/restore-space/{id}', [SpaceController::class, 'restore'])->name('restore_space');
        Route::get('/restore-meeting/{id}', [MeetingController::class, 'restore'])->name('restore_meeting');
    });
    Route::post('/update-task', [TaskController::class, 'update'])->name('update_task');
    Route::get('/space', [SpaceController::class, 'index'])->name('space');
    Route::get('/meetings', [MeetingController::class, 'index'])->name('meetings');
    Route::post('/showTask', [TaskController::class, 'showTaskList'])->name('show_task');
    Route::post('/showSpaces', [SpaceController::class, 'showSpaces'])->name('show_spaces');
    Route::post('/showTasks', [TaskController::class, 'showTasks'])->name('show_tasks');
    Route::get('/get-users', [SpaceController::class, 'users'])->name('get_users');
    Route::get('/get-all-tasks/{id}', [TaskController::class, 'allTasks'])->name('get_all_tasks');
    Route::get('/get-meeting/{id}', [MeetingController::class, 'edit'])->name('get_meeting');
});