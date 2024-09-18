<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Http\Requests\StoreTodoListRequest;
use App\Http\Requests\UpdateTodoListRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Event\Tracer\Tracer;

class TodoListController extends Controller
{
   public function createTodo(StoreTodoListRequest $request) 
   {
      if (!Auth::check()) {
         return response()->json([
             'status' => false,
             'message' => 'User not authenticated'
         ], 401);
     }
 
       $todoList = TodoList::create([
           'todo' => $request->todo,
           'userId' => Auth::user()->id
       ]);
   
       return response()->json([
           'status' => true,
           'todo' => $todoList
       ], 201);
   }


   public function completeTodo(TodoList $todo)
   {
       $todo->completed = $todo->completed ? 0 : 1;

       $todo->save();
   
       return response()->json([
           'message' => 'Todo updated successfully',
           'todo' => $todo
       ], 200);
   }

}
