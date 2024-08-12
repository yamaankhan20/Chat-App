<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function online_user(request $request){


        $all_user = User::where('id', '!=', $request['user_id'])->get();

        if(!empty($all_user)){
            return response()->json(data: ["users"=> $all_user]);
        }
        return response()->json(data: ["users"=> '']);
    }
}
