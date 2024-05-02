<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Interfaces\IUserManagement;
use App\Interfaces\MessagesInterfaces\IMessageQuery;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public $user_service;
    public $message_service;
    public function __construct(IUserManagement $user_service, IMessageQuery $message_service)
    {
        $this->user_service = $user_service;
        $this->message_service = $message_service;
    }
    public function generateToken()
    {
        $user = User::find(1);
        $token = JWTAuth::fromUser($user);
        return response()->json(['message' => 'authenticated'], 200, ['token' => $token]);
    }

    /**
     * Get users with their last message.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function getUsersWithLastMessage()
    {
        try {
            $users = $this->user_service->getUsers(Auth::user()->id);
            $users = $this->message_service->getLastMessageBetweenUsers($users, Auth::user()->id);
            return response()->json(['success' => 'true', 'users' => $users], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
