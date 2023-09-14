<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Space;
use App\Models\Task;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $all_tasks = Task::count();
        $tasks_pending = Task::where('task_state', 'En cours')->count();
        $tasks_finished = Task::where('task_state', 'Achevé')->count();
        $all_users = User::count();
        $users = User::take('3')->get();
        $count = $all_users - count($users);
        $all_meetings = Meeting::count();
        $meetings = Meeting::take('10')->get();
        $spaces = Space::withCount('tasks')->get();
        return view('home', [
            'all_tasks'=> $all_tasks, 
            'tasks_pending'=> $tasks_pending, 
            'tasks_finished'=> $tasks_finished, 
            'all_users'=> $all_users, 
            'users'=> $users, 
            'count'=> $count, 
            'all_meetings'=> $all_meetings, 
            'meetings'=> $meetings, 
            'spaces'=> $spaces, 
        ]);
    }
}
