<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubTaskController;

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::get('/tasks/{taskId}', [TaskController::class, 'show'])->name('tasks.show');


Route::get('/subtasks', [SubTaskController::class, 'index'])->name('subtasks.index');
Route::post('/tasks/{task}/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
Route::post('/subtasks/{subtask}/toggle-status', [SubTaskController::class, 'toggleStatus'])->name('subtasks.toggleStatus');
Route::get('/subtasks/{subtask}/edit', [SubTaskController::class, 'edit'])->name('subtasks.edit');
Route::put('/subtasks/{id}', [SubtaskController::class, 'update'])->name('subtasks.update');
Route::delete('/subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');



