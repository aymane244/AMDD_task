<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            'task_name' => 'required|string',
        ]);
        if($validate->fails()){
            return response()->json(['errors' => $validate->errors()]);
        }else{
            $task = new Task();
            $task->task_name = $request->task_name;
            $task->task_begin_date = $request->begin_date;
            $task->task_finish_date = $request->finish_date;
            $task->user_id = $request->user_comittee;
            $task->space_id = $request->space_id;
            $task->save();
            return response()->json(['message' => "La tâche ".$task->task_name." a été crée avec succèss"]);
        }
    }
    public function showTaskList(Request $request){
        $task_admin = Task::where('space_id', $request->space_id)
        ->where('deleted_at', null)
        ->get();
        $task_user = Task::where('space_id', $request->space_id)
            ->where('user_id', Auth::user()->id)
            ->where('deleted_at', null)
            ->get();
        if(count($task_user) > 0 || count($task_admin) > 0){
            return response()->json(['task_user' => $task_user, 'task_admin' => $task_admin]);
        }else{
            return response()->json(['error' => 'Pas de tâche en ce moment']);
        }
    }
    public function showTasks(Request $request){
        $tasks = Task::with('user')->find($request->task_id);
        if(!$tasks){
            return response()->json(['error' => "Pas de tâche à afficher en ce moment"]);
        }else{
            return response()->json(['tasks' => $tasks]);
        }
    }
    public function edit($id){
        $task = Task::with('user')->find($id);
        return response()->json(['task' => $task]);
    }
    public function allTasks($id){
        $task = Task::with('user')->find($id);
        return response()->json(['task' => $task]);
    }
    public function update(Request $request){
        $task = Task::find($request->task_id);
        if($task){
            if($request->task_name != ''){
                $task->task_name = $request->task_name;
            }
            if($request->task_user != ''){
                $task->user_id = $request->task_user;
            }
            if($request->task_date != ''){
                $task->task_finish_date = $request->task_date;
            }
            if($request->task_priority != ''){
                $task->task_priority = $request->task_priority;
            }
            if($request->task_state != ''){
                $task->task_state = $request->task_state;
            }
            if($request->task_priority != '' && $request->task_date != '' && $request->task_user != '' && $request->task_name != '' && $request->task_state != ''){
            }
            $task->update();
            return response()->json(['message' => "La tâche a été modifiée avec succès"]);
        }else{
            return response()->json(['message' => "La tâche n'a pas été trouvée"], 404);
        }
    }
    public function delete($id){
        $task = Task::withTrashed()->find($id);
        if($task){
            $task->forceDelete();
            return response()->json(['message' => 'La tâche '.$task->task_name.' a été bien supprimée']);
        }
    }
    public function archive($id){
        $task = Task::find($id);
        if($task){
            $task->delete();
            return response()->json(['message' => 'La tâche '.$task->task_name.' a été bien supprimée']);
        }
    }
    public function restore($id){
        $task = Task::withTrashed()->find($id);
        if($task){
            $task->restore();
            return response()->json(['message' => "La tâche ".$task->task_name." a été restaurée"]);
        }
    }
}
