<?php

use App\Http\Controllers\GameController;
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


Route::get('/', [GameController::class, 'index'])->name('game.index');
Route::post('logout', [GameController::class, 'logout'])->name('user.logout')->middleware('auth');
Route::post('game/start', [GameController::class, 'start'])->name('game.start');
Route::post('game/finish', [GameController::class, 'finish'])->name('game.finish')->middleware('auth');
