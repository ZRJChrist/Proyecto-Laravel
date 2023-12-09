<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

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

//CRUD tareas
Route::get('/Task/Add', [TaskController::class, 'createTaskView'])->name('createTaskView');
Route::post('/Task/Add', [TaskController::class, 'createTask'])->name('createTask');

Route::get('/Tasks/{page?}', [TaskController::class, 'readTaskTableView'])->name('readTasks');


Route::get('/Tasks/Update/{id}', [TaskController::class, 'updateTaskView'])->name('editask');
Route::post('/Tasks/Update/{id}', [TaskController::class, 'updateTask'])->name('updateTask');

Route::get('/Tasks/Show/{id}', [TaskController::class, 'readTaskDetailsView'])->name('showTask');
Route::get('/Tasks/getArchive/{id}', [TaskController::class, 'getArchive'])->name('storage');

Route::get('/Tasks/Delete/{id}', [TaskController::class, 'deleteTaskView'])->name('confirmDeletTask');
Route::post('/Tasks/Delete/{id}', [TaskController::class, 'deleteTask'])->name('deleteTask');

//CRUD usuarios

Route::get('/Users/{page?}', [UserController::class, 'readUserView'])->name('readUsers');

Route::get('/User/Add', [UserController::class, 'createUserView'])->name('createUsersView');
Route::post('/User/Add', [UserController::class, 'createUser'])->name('createUsers');


Route::get('/Users/Update/{id}', [UserController::class, 'updateUserView'])->name('editUser');
Route::post('/Users/Update/{id}', [UserController::class, 'updateUser'])->name('updateUser');
