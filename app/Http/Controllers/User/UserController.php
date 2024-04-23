<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function generateToken(){
        $user = User::find(1);
        $token = JWTAuth::fromUser($user);
        return response()->json(['message'=>'authenticated'],200,['token' => $token]);
    }

    
}
