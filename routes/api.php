<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TodoListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');




Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

Route::middleware('auth:api')->group(function(){
    Route::post('/', [TodoListController::class , 'createTodo']);
    Route::patch('/complete/{todo}', [TodoListController::class , 'completeTodo']);
    Route::get('/', [TodoListController::class, 'userTodo']);
    Route::get('/', [TodoListController::class, 'completedTodo']);
});