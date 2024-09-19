<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Http\Requests\StoreTodoListRequest;
use App\Http\Requests\UpdateTodoListRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;
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
           'userId' => Auth::user()->userId
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

   public function userTodos()
   {
        $todo = TodoList::where('userId', Auth::user()->id)
                ->orderBy('created_at', 'desc')
                ->get();
        
        return response()->json([
            'status' => TRUE,
            'todo' => $todo
        ]);
   }

   public function fetchCompletedTodo()
   {
    $completedTodos = TodoList::where('userId', Auth::user()->id)
    ->where('completed', 1)
    ->get();

        return response()->json([
            'status' => true,
            'todo' => $completedTodos
        ]);
   }

}
