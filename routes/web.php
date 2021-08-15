<?php

use App\Http\Controllers\DivisionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
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
    return view('login');
})->name('login');

Route::post('/postlogin', [LoginController::class, 'postLogin'])->name('postlogin');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware(['auth']);
Route::post('/vote/store/{id}', [VoteController::class, 'store'])->name('vote.store')->middleware(['auth', 'ceklevel:user']);

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(['auth']);
Route::group(['middleware' => ['auth', 'ceklevel:admin']], function () {
    Route::resource('/division', DivisionController::class);
    Route::resource('/user', UserController::class);
    
    Route::get('/poll', [PollController::class, 'index'])->name('poll');
    Route::get('/poll/create', [PollController::class, 'create'])->name('poll.create');
    Route::post('/poll/store', [PollController::class, 'store'])->name('poll.store');
    Route::get('/poll/edit/{id}', [PollController::class, 'edit'])->name('poll.edit');
    Route::put('/poll/update/{id}', [PollController::class, 'update'])->name('poll.update');
    Route::post('/poll/delete/{id}', [PollController::class, 'destroy'])->name('poll.delete');
    Route::get('/poll/del/{id}', [PollController::class, 'del'])->name('poll.del');
});