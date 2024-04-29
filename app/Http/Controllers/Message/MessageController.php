<?php

namespace App\Http\Controllers\Message;

use App\Events\SendMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\GetMessagesRequest;
use App\Http\Requests\Message\markAsReadRequest;
use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Requests\User\VerifyUserIdRequest;
use App\Interfaces\IMessage;
use App\Interfaces\IUserRepository;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public $message_service;
    public $user_service;
    public function __construct(IMessage $message_service,IUserRepository $user_service)
    {
        $this->message_service = $message_service;
        $this -> user_service = $user_service;
    }

    /**
     * Send a message.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing the message content, recipient type, and recipient entity ID.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success of the message sending operation.
     * @throws \Throwable If an exception occurs during the message sending process.
     */
    public function sendMessage(SendMessageRequest $request)
    {
        try {
            $message = $this->message_service->sendMessage($request->content, Auth::user());
            SendMessageEvent::dispatch($message, $request->recipient_type, $request->recipient_entity_id);
            return response()->json(['success' => true], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
    /**
     * Get messages.
     *
     * @param \App\Http\Requests\Message\GetMessagesRequest $request The HTTP request containing the recipient entity ID and recipient type.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the success status and the retrieved messages.
     * @throws \Throwable If an exception occurs during the message retrieval process.
     */
    public function getMessages(GetMessagesRequest $request)
    {
        try {
            $message = $this->message_service->getMessages($request->recipient_entity_id, $request->recipient_type,Auth::user());
            return response()->json(['success' => true, 'messages' => $message], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Get message history.
     *
     * This method retrieves the history of messages for a specific recipient entity ID and recipient type.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the success status and the retrieved messages.
     * @throws \Throwable If an exception occurs during the message retrieval process.
     */
    public function getMessageHistory(VerifyUserIdRequest $request)
    {
        try {
            if ($request -> filled('id')) {
                $user = $this -> user_service -> getUserForId($request -> id);
            }
            else{
                $user =  Auth::user();
            }
            $message = $this->message_service->getMessageHistory($user);
            return response()->json(['success' => true, 'messages' => $message], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Mark a message as read.
     *
     * @param \App\Http\Requests\Message\markAsReadRequest $request The HTTP request containing the message ID to be marked as read.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success of the message marking operation.
     * @throws \Throwable If an exception occurs during the message marking process.
     */
    public function markAsRead(markAsReadRequest $request)
    {
        try {
            $this->message_service->markAsRead($request->id,Auth::user());
            return response()->json(['success' => true], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
