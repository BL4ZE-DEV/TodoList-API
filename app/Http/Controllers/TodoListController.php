<?php

namespace App\Http\Controllers;

use App\Enums\TodoStatus;
use App\Models\TodoList;
use App\Http\Requests\StoreTodoListRequest;
use App\Http\Requests\UpdateTodoListRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
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
      $todo->status = $todo->status == TodoStatus::PENDING->value ? TodoStatus::COMPLETED  : $todo->status ;

        $todo->save();

       return response()->json([
           'message' => 'Todo Completed',
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
     $completedTodos = TodoList::where('userId', Auth::user()->userId)
        ->where('status', TodoStatus::COMPLETED)
        ->paginate();

        return response()->json([
            'status' => true,
            'todo' => $completedTodos
        ]);
   }

   public function fetchPendingTodo()
   {
    $pendingTodos = TodoList::where('userId', Auth::user()->userId)
    ->where('status', TodoStatus::PENDING)
    ->get();

    return response()->json([
        'status' => true,
        'todo' => $pendingTodos
    ]); 
   }
   

   public function updateTodo(UpdateTodoListRequest $request, TodoList $todo)
   {
       
           $todo->update([
               'todo' => $request->todo ?? $todo->todo
           ]);
   
           return response()->json([
               'message' => "Todo updated successfully!"
           ], 200);   
   }
   

   public function deleteTodo(TodoList $todo)
   {
        $todo->delete();

        return response()->json([
            'message' => 'Todo deleted successfully!'
        ], 200);
   }

   public function rating()
   {
        $todos = TodoList::where('userId', Auth::user()->userId)->get();
        
        $todoCompleted = [];

        foreach($todos as $todo){
            if($todo->status == TodoStatus::COMPLETED->value){
                $todoCompleted[]= TodoStatus::COMPLETED;
            }
            
        }

        $todosCount = count($todos);
        $completedTodosCount = count($todoCompleted);

        if($todosCount === 0){
            return response()->json([
                'message' => 'success',
                'percentage' => '0%',
                'remark' => 'No todos available'
            ]);
        }

      $percentage  =  ($completedTodosCount / $todosCount)*100 ;
    
      $remark = $percentage < 50 ? 'unsatisfactory' : 'Good';

      return response()->json([
        'message' => 'success',
        'percentage' => number_format($percentage , 2).'%',
        'remark' => $remark
      ]);
        
   }

   public function search()
   {
       $todo = TodoList::where(function($query){
           $query->when(request()->filled('search'), function($query){
               return $query->where('todo', 'LIKE', '%'.request('search').'%');
           });
       })->paginate(); 
   
       return response()->json([
           'status' => true,
           'message' => 'todo fetched successfully!',
           'data' => $todo
       ]);
   }
}
