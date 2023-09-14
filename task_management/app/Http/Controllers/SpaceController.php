<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Space;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SpaceController extends Controller
{
    public function index(){
        $users= User::orderBy('first_name', 'ASC')->get();
        $user_tasks = Task::where('user_id', Auth::user()->id)->get();
        $all_tasks = Task::all();
        $user_spaces = Task::select('space_id', DB::raw('MAX(id) as max_id'))
        ->where('user_id', Auth::user()->id)
        ->groupBy('space_id')
        ->with('space')
        ->get();
        $all_spaces = Space::all();
        return view('space', [
            'users'=> $users, 
            'all_tasks' => $all_tasks, 
            'user_tasks' => $user_tasks,
            'user_spaces' => $user_spaces,
            'all_spaces' => $all_spaces,
        ]);
    }
    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            'space_name' => 'required|string',
            'description' => 'required|string',
        ]);
        if($validate->fails()){
            return response()->json(['errors' => $validate->errors()]);
        }else{
            $space = new Space();
            $space->space_name = $request->space_name;
            $space->description = $request->description;
            $space->save();
            return response()->json(['message' => "L'espace ".$space->space_name." a été crée avec succèss"]);
        }
    }
    public function showSpaces(Request $request){
        $tasks = Task::with('user')
        ->where('space_id', $request->space_id)
        ->where('deleted_at', null)
        ->get();
        $taskCountAll = $tasks->count();
        $taskCountUser = $tasks->where('user_id', Auth::user()->id)->count();
        $spaceName = Space::find($request->space_id);
        return response()->json([
            'tasks' => $tasks, 
            'taskCountAll' => $taskCountAll, 
            'taskCountUser' => $taskCountUser, 
            'spaceName' => $spaceName
        ]);
    }
    public function delete($id){
        $space = Space::withTrashed()->find($id);
        if($space){
            $space->forceDelete();
            return response()->json(['message' => "L'espace ".$space->space_name." a été bien supprimé"]);
        }
    }
    public function showArchive(){
        $tasks = Task::all();
        $allTasks = Task::withTrashed()->get();
        $spaces = Space::withTrashed()->get();
        $meetings = Meeting::withTrashed()->get();
        return view('archives.index', [
            'spaces' => $spaces, 
            'tasks' => $tasks, 
            'allTasks' => $allTasks,
            'meetings' => $meetings,
        ]);
    }
    public function archive($id){
        $space = Space::find($id);
        if($space){
            $space->delete();
            return response()->json(['message' => "L'espace ".$space->space_name." a été bien archivé"]);
        }
    }
    public function restore($id){
        $space = Space::withTrashed()->find($id);
        if($space){
            $space->restore();
            return response()->json(['message' => "L'espace ".$space->space_name." a été restaurée"]);
        }
    }
    public function users(){
        $users = User::all();
        return response()->json(['users' => $users]);
    }
}
