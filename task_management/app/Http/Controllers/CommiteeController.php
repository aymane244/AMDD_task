<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommiteeController extends Controller
{
    public function committeeUser($userId){
        $user = User::where('id', $userId)->first();
        if($user){
            $committee = $user->committee;
            return response()->json(['committee' => $committee]);
        }
        return response()->json(['error' => 'User not found.']);
    }
}
