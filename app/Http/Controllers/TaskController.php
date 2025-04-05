<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
   
    public function index()
{
    return view('tasks.index'); 
}

public function fetch(Request $request)
{
    if ($request->has('all') && $request->all == 'true') {
        return response()->json(Task::all());
    } else {
        return response()->json(Task::where('completed', false)->get());
    }
}

public function store(Request $request)
{
    $task = Task::create(['title' => $request->title]);
      return response()->json($task);
}

public function toggle(Task $task)
{
    $task->completed = !$task->completed;
      $task->save();
    return response()->json($task);
}

public function delete_it(Task $task)
{
    $task->delete();
      return response()->json(['success' => true]);
}
}