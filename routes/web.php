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
//Muestra el formulario de inicio de sesión.
Route::get('/', [LoginController::class, 'getSignIn'])->name('login');
//Ruta para intentar iniciar sesión. Llama al método attemptSignIn en el controlador LoginController.
Route::post('/', [LoginController::class, 'attemptSignIn'])->name('PostLogin');
//Ruta para cerrar sesión. Llama al método logOutSession en el controlador LoginController.
Route::get('/Logout', [LoginController::class, 'logOutSession'])->name('logout');

//CRUD tareas

//Ruta para mostrar el formulario de creación de tarea. Llama al método createTaskView en el controlador TaskController.
Route::get('/Task/Add', [TaskController::class, 'createTaskView'])->name('createTaskView');
//Ruta para crear una tarea. Llama al método createTask en el controlador TaskController.
Route::post('/Task/Add', [TaskController::class, 'createTask'])->name('createTask');

//Ruta para mostrar la tabla de tareas. Llama al método readTaskTableView en el controlador TaskController.
Route::get('/Tasks/{page?}', [TaskController::class, 'readTaskTableView'])->name('readTasks');

//Ruta para mostrar el formulario de actualización de tarea. Llama al método updateTaskView en el controlador TaskController.
Route::get('/Tasks/Update/{id}', [TaskController::class, 'updateTaskView'])->name('editask');
//Ruta para actualizar una tarea. Llama al método updateTask en el controlador TaskController.
Route::post('/Tasks/Update/{id}', [TaskController::class, 'updateTask'])->name('updateTask');

//Ruta para mostrar los detalles de una tarea. Llama al método readTaskDetailsView en el controlador TaskController.
Route::get('/Tasks/Show/{id}', [TaskController::class, 'readTaskDetailsView'])->name('showTask');
//Ruta para obtener el archivo asociado a una tarea. Llama al método getArchive en el controlador TaskController.
Route::get('/Tasks/getArchive/{id}', [TaskController::class, 'getArchive'])->name('storage');

// Ruta para mostrar la confirmación de eliminación de una tarea. Llama al método deleteTaskView en el controlador TaskController.
Route::get('/Tasks/Delete/{id}', [TaskController::class, 'deleteTaskView'])->name('confirmDeletTask');
// Ruta para eliminar una tarea. Llama al método deleteTask en el controlador TaskController.
Route::post('/Tasks/Delete/{id}', [TaskController::class, 'deleteTask'])->name('deleteTask');

//CRUD usuarios
//Ruta para mostrar la tabla de usuarios. Llama al método readUserView en el controlador UserController.
Route::get('/Users/{page?}', [UserController::class, 'readUserView'])->name('readUsers');

//Ruta para mostrar el formulario de creación de usuario. Llama al método createUserView en el controlador UserController
Route::get('/User/Add', [UserController::class, 'createUserView'])->name('createUsersView');
//Ruta para crear un usuario. Llama al método createUser en el controlador UserController.
Route::post('/User/Add', [UserController::class, 'createUser'])->name('createUsers');

// Ruta para mostrar el formulario de actualización de usuario. Llama al método updateUserView en el controlador UserController.
Route::get('/Users/Update/{id}', [UserController::class, 'updateUserView'])->name('editUser');
//Ruta para actualizar un usuario. Llama al método updateUser en el controlador UserController.
Route::post('/Users/Update/{id}', [UserController::class, 'updateUser'])->name('updateUser');
