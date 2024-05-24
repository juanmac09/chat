<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\VerifyUserIdRequest;
use App\Interfaces\IUserManagement;
use App\Interfaces\IUserRepository;
use App\Interfaces\MessagesInterfaces\IMessageQuery;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public $user_service;
    public $user_repository;
    public $message_service;
    public function __construct(IUserManagement $user_service, IMessageQuery $message_service, IUserRepository $user_repository)
    {
        $this->user_service = $user_service;
        $this->message_service = $message_service;
        $this->user_repository = $user_repository;
    }
    public function generateToken()
    {
        $user = User::find(4);
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

    /**
     * Get a user by their ID.
     *
     * @param VerifyUserIdRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function getUser(VerifyUserIdRequest $request)
    {
        try {
            $user = Auth::user();
            if ($request->has('id')) {
                $user = $this->user_repository->getUserForId($request->id);
            }
            return response()->json(['success' => 'true', 'user' => $user], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }



    /**
     * Get user data with JWT.
     *
     * This method retrieves user data using the JWT (JSON Web Token) authentication.
     *
     * @return void
     * @throws \Throwable If an exception occurs during the process.
     */
    public function getUserDataWithJwt()
    {
        try {
            $user = $this->user_service->getUserDataWithJWT();
            return response()->json(['success' => true, 'user' => $user], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
