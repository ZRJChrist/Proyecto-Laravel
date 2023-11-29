<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaskController;


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
Route::get('/', [LoginController::class, 'getSignIn'])->name('login');
Route::post('/', [LoginController::class, 'attemptSignIn'])->name('PostLogin');

Route::get('/Logout', [LoginController::class, 'logOutSession'])->name('logout');

//Registro
Route::get('/Signup', [LoginController::class, 'getSigUp'])->name('register');
Route::post('/Signup', [LoginController::class, 'attentSignUp'])->name('PostRegister');


//CRUD tareas
Route::get('/Add', [TaskController::class, 'add'])->name('addTask');
Route::post('/Add', [TaskController::class, 'create'])->name('addPost');

Route::get('/Tasks/{page?}', [TaskController::class, 'list'])->name('listTask');

Route::get('/Tasks/Update/{id}', [TaskController::class, 'edit'])->name('editask');
Route::post('/Tasks/Update/{id}', [TaskController::class, 'update'])->name('updateTask');

Route::get('/Tasks/Show/{id}', [TaskController::class, 'show'])->name('showTask');

Route::get('/Tasks/Delete/{id}', [TaskController::class, 'confirm'])->name('confirmDeletTask');
Route::post('/Tasks/Delete/{id}', [TaskController::class, 'delete'])->name('deleteTask');
