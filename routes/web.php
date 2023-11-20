<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;

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

Route::get('/login', [LoginController::class, 'getLogin'])->name('login');

Route::post('/login', [LoginController::class, 'attemptLogin'])->name('PostLogin');

Route::get('/logout', [LoginController::class, 'logOutSession'])->name('logout');

Route::get('/registro', [RegisterController::class, 'getLogin'])->name('register');

Route::post('/registro', [RegisterController::class, 'attentRegister'])->name('PostRegister');

Route::get('/', [HomeController::class, 'getHome'])->name('home');
