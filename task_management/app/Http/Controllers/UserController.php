<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create(Request $request){
        $user = new User();
        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $imagePath = $image->storeAs('images/', $imageName, 'public');
            $user->image = $imagePath;
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->is_admin = 0;
        $user->committee_id = $request->comittee;
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect('login')->with('success', 'You registerd successfully');
    }
}
