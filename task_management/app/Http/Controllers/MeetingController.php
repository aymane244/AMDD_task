<?php

namespace App\Http\Controllers;

use App\Mail\SendMeetingInvitationMail;
use App\Models\Committee;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{
    public function index(){
        $users = User::with('committee')->get();
        $committes = Committee::has('user')->get();
        $meetings = Meeting::with('users')->get();
        return view('meetings.index', ['users' => $users, 'committes' => $committes, 'meetings' => $meetings]);
    }
    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            'meeting_name' => 'required|string',
            'meeting_object' => 'required|string',
            'meeting_date' => 'required',
            'meeting_link' => 'required|string',
            'user_invitations' => 'required',
        ]);
        if($validate->fails()){
            return response()->json(['errors' => $validate->errors()]);
        }else{
            $meeting = new Meeting();
            $meeting->meeting_name = $request->meeting_name;
            $meeting->meeting_object = $request->meeting_object;
            $meeting->meeting_date = $request->meeting_date;
            $meeting->meeting_link = $request->meeting_link;
            $meeting->save();
            $users = User::whereIn('id', $request->user_invitations)->get();
            $meeting->users()->sync($users, ['created_at' => now(), 'updated_at' => now(),]);
            $mailData = [
                'sujet' => $request->meeting_object,
                'title' => $request->meeting_name,
                'body' => $request->meeting_link,
            ];
            $recipientEmails = $users->pluck('email')->toArray();
            // Mail::to($recipientEmails)->send(new SendMeetingInvitationMail($mailData));
            return response()->json(['message' => "La réunion a été crée avec succèss un email a été envoyé aux invités"]);
        }
    }
    public function edit($id){
        $users = User::all();
        $meeting = Meeting::with('users')->find($id);
        return response()->json(['meeting' => $meeting, 'users' => $users]);
    }
    public function update(Request $request){
        $meeting = Meeting::find($request->meeting_id);
        $meeting->meeting_name = $request->meeting_name;
        $meeting->meeting_object = $request->meeting_object;
        $meeting->meeting_date = $request->meeting_date;
        $meeting->meeting_link = $request->meeting_link;
        $meeting->save();
        $meeting->users()->sync($request->user_invitations, ['updated_at' => now(),]);
        return response()->json(['message' => "La réunion a été modifiée avec succèss"]);
    }
    public function delete($id){
        $meeting = Meeting::withTrashed()->find($id);
        if($meeting){
            $meeting->forceDelete();
            return response()->json(['message' => "La réunion ".$meeting->meeting_name." a été bien supprimée"]);
        }
    }
    public function archive($id){
        $meeting = Meeting::find($id);
        if($meeting){
            $meeting->delete();
            return response()->json(['message' => "La réunion ".$meeting->meeting_name." a été bien archivé"]);
        }
    }
    public function restore($id){
        $meeting = Meeting::withTrashed()->find($id);
        if($meeting){
            $meeting->restore();
            return response()->json(['message' => "La réunion ".$meeting->meeting_name." a été restaurée"]);
        }
    }
}
