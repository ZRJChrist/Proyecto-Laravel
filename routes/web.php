<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AddController;
use App\Http\Controllers\ListController;


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

//Login
Route::get('/login', [LoginController::class, 'getLogin'])->name('login');

Route::post('/login', [LoginController::class, 'attemptLogin'])->name('PostLogin');

Route::get('/logout', [LoginController::class, 'logOutSession'])->name('logout');

//Registro
Route::get('/registro', [RegisterController::class, 'getLogin'])->name('register');

Route::post('/registro', [RegisterController::class, 'attentRegister'])->name('PostRegister');

//Home
Route::get('/', [HomeController::class, 'getHome'])->name('home');

//Agregar tareas
Route::get('/Add', [AddController::class, 'get'])->name('addTask');

Route::post('/Add', [AddController::class, 'post'])->name('addPost');

//RUD tareas
Route::get('/Tasks/{page?}', [ListController::class, 'get'])->name('listTask');

Route::get('/Tasks/Update/{id}', [ListController::class, 'edit'])->name('editask');
Route::post('/Tasks/Update/{id}', [ListController::class, 'update'])->name('updateTask');


Route::get('/Tasks/Show/{id}', [ListController::class, 'show'])->name('showTask');

Route::get('/Tasks/Delete/{id}', [ListController::class, 'delete'])->name('deleteTask');
