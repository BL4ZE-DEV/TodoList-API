<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoListController;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');




Route::post('/register', [AuthenticationController::class, 'register'])->name('register');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function(){
    Route::prefix('/todo')->group(function()
    { 
        Route::post('/', [TodoListController::class , 'createTodo']);
        Route::get('/', [TodoListController::class, 'userTodo']);
        Route::patch('/{todo}/complete', [TodoListController::class , 'completeTodo']);
        Route::put('/{todo}/update', [TodoListController::class, 'updateTodo']);
        Route::delete('/{todo}/delete', [TodoListController::class, 'deleteTodo']);
        Route::get('/completed', [TodoListController::class, 'fetchCompletedTodo']);
        Route::get('/pending', [TodoListController::class, 'fetchPendingTodo']);
        Route::get('/rating', [TodoListController::class, 'rating']);
    });

    Route::prefix('/profile')->group(function()
    {
        Route::get('/', [ProfileController::class, 'view']);
        Route::put('/update', [ProfileController::class, 'update']);
        Route::patch('/updatePassword', [ProfileController::class, 'updatePassword']);
    });
   
});