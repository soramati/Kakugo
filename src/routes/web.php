<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GoalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiController;
use App\Models\Goal;

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


Route::controller(UserController::class)->group(function () {
    Route::get('/welcome', 'welcome')->name('welcome');
    Route::get('/show/{hashID}', 'show')->name('show');
});


Route::controller(GoalController::class)->group(function () {
    Route::get('/shere/{hashID}', 'shere')->name('shere');
});
// 本の削除



Route::get('api/users/getPublishedGoals', [ApiController::class, 'apiGetPublishedGoals'])->name('apiGetPublishedGoals');
Route::get('api/shere/{hashID}', [UserController::class, 'shere'])->name('apiShere');
Route::post('/destroy/{id}', [ApiController::class, 'destroy'])->name('destroy');
Route::post('/reset/{id}', [ApiController::class, 'reset'])->name('reset');

Route::controller(ApiController::class)->middleware(['auth'])->group(function () {

    Route::post('/good/{hashID}', 'good')->name('good');
    Route::get('/api/get', 'apiIndex');
    Route::post('/api/store', 'apiStore');
    Route::delete('/goals/{goal}', 'apiDelete');
    Route::post('/api/start/{id}', 'apiStart');
    Route::post('done/{id}', 'apiDone');
    Route::post('edit/{id}', 'apiEdit');
    Route::get('api/getUserName', 'apiGetUserName');
    Route::post('/api/achieved/{id}', 'apiAchieved');
    Route::post('api/publish', 'apiPublish');
    Route::post('api/addGood/{id}', 'apiAddGood');
    Route::post('api/getFollowing/{id}', 'getFollowingNum');
    Route::get('/api/users/getFollowers', 'getFollower');
    Route::get('/api/reset', 'reset');

    // Route::get('api/users/getPublishedGoals', 'apiGetPublishedGoals');
});
// ここからApi
Route::controller(UserController::class)->middleware(['auth'])->group(function () {
    Route::get('/getApi', 'index')->name('index');
});

Route::controller(UserController::class)->middleware(['auth'])->group(function () {
    Route::get('/testApi', 'apiindex')->name('index');
});
Route::controller(GoalController::class)->middleware(['auth'])->group(function () {
    Route::get('/settime', 'apiindex')->name('index');
    Route::post('/api/store/goal', 'apiStore')->name('store');
});

// ここまでApi

Route::get('/api', 'TestApiController@store');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::controller(UserController::class)->middleware(['auth'])->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/dashboard', 'index')->name('index');
});

Route::get('/admin', [UserController::class, 'admin'])->middleware(['auth', 'admin'])->name('admin');

Route::controller(GoalController::class)->middleware(['auth'])->group(function () {
    Route::patch('/goals/reset', 'resetGoalsSet')->name('resetGoalsSet');
    Route::post('/goals', 'store')->name('store');
    Route::get('/goals/create', 'create')->name('create');
    Route::patch('/goals/done/{goal}', 'done')->name('done');
    Route::patch('/goals/set/{goal}', 'set')->name('set');
    Route::get('/goals/{goal}', 'show')->name('show');
    Route::put('/goals/{goal}', 'update')->name('update');
    Route::delete('/goals/{goal}', 'delete')->name('delete');
    Route::get('/goals/{goal}/edit', 'edit')->name('edit');
});



Route::get('/admin', [App\Http\Controllers\UserController::class, 'admin'])->middleware(['auth', 'verified'])->name('admin');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// フォールバックルート
Route::fallback(function () {
    return redirect('/welcome');
});

require __DIR__ . '/auth.php';
