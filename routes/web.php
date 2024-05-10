<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware('auth')->group(function () {
    Route::post('/store-todo', [App\Http\Controllers\HomeController::class, 'storeTodo'])->name('store.todo');
    Route::put('/update-todo/{id}', [App\Http\Controllers\HomeController::class, 'updateTodo'])->name('update.todo');
    Route::put('/update-todo-status/{id}', [App\Http\Controllers\HomeController::class, 'updateTodoStatus'])->name('update.todo.status');
    Route::delete('/delete-todo/{id}', [App\Http\Controllers\HomeController::class, 'deleteTodo'])->name('delete.todo');

});