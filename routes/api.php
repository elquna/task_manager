<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('create-user', [UserController::class, 'create']);
Route::post('login-user', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/fetch-tasks', [TaskController::class, 'index'])->name('fetch-task');
    Route::get('/get-task-by-id/{id}', [TaskController::class, 'show'])->name('get-task');
    Route::post('create-task', [TaskController::class, 'store'])->name('create-task');
    Route::post('/update-task/{id}', [TaskController::class, 'update']);
    Route::delete('delete-tasks/{id}', [TaskController::class, 'destroy']);
});