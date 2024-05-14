<?php

namespace App\Http\Controllers\Message;

use App\Events\markAsReadAMessageEvent;
use App\Events\SendMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\GetMessagesRequest;
use App\Http\Requests\Message\markAllMessageAsReadRequest;
use App\Http\Requests\Message\markAsReadRequest;
use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Requests\User\VerifyUserIdRequest;
use App\Interfaces\IUserRepository;
use App\Interfaces\MessagesInterfaces\IMessageQueryForGroups;
use App\Interfaces\MessagesInterfaces\IMessageQueryForUsers;
use App\Interfaces\MessagesInterfaces\IMessageSender;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public $messageSenderService;
    public $messageQueryUserService;
    public $messageQueryGroupService;
    public $user_service;
    public function __construct(IUserRepository $user_service, IMessageSender $messageSenderService, IMessageQueryForUsers $messageQueryUserService, IMessageQueryForGroups $messageQueryGroupService)
    {
        $this->user_service = $user_service;
        $this->messageSenderService = $messageSenderService;
        $this->messageQueryUserService = $messageQueryUserService;
        $this->messageQueryGroupService = $messageQueryGroupService;
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
            $message = $this->messageSenderService->sendMessage($request->content, Auth::user());
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
            $message = ($request->recipient_type == 1) ? $this->messageQueryUserService->getMessagesBetweenUsers(Auth::user()->id, $request->recipient_entity_id) : $this->messageQueryGroupService->getMessagesFromAGroup($request->recipient_entity_id);
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
            if ($request->filled('id')) {
                $user = $this->user_service->getUserForId($request->id);
            } else {
                $user =  Auth::user();
            }
            $message = [];
            $message['users'] = $this->messageQueryUserService->getChatHistoryBetweenUsers($user->id);
            $message['groups'] = $this->messageQueryGroupService->getChatHistoryBetweenUserAndGroups($user->id);
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
            $message = $this->messageSenderService->markAsRead($request->id, Auth::user());
            markAsReadAMessageEvent::dispatch(Auth::user(),1 ,$message);
            return response()->json(['success' => true], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
    /**
     * Mark all messages as read for the specified sender and user.
     *
     * @param \App\Http\Requests\Message\markAllMessageAsReadRequest $request The HTTP request containing the sender ID, user ID, and recipient type.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success of the message marking operation.
     * @throws \Throwable If an exception occurs during the message marking process.
     */
    public function markAllMessagesAsRead(markAllMessageAsReadRequest $request)
    {
        try {

            $this->messageSenderService->markAllMessagesAsRead($request->id_sender, Auth::user()->id, $request->type);
            markAsReadAMessageEvent::dispatch(Auth::user(),2,null,$request->id_sender,$request->type);
            return response()->json(['success' => true], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }


    /**
     * Count unread messages for the authenticated user.
     *
     * This method retrieves the count of unread messages for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the success status and the count of unread messages.
     * @throws \Throwable If an exception occurs during the message retrieval process.
     */
    public function countMessagesNotRead()
    {
        try {
            $unreadMessages = $this->messageQueryUserService->countMessageNotReads(Auth::user()->id);
            return response()->json(['success' => true, 'unreadMessages' => $unreadMessages], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }


    /**
     * Count unread messages for the authenticated user in a group context.
     *
     * This method retrieves the count of unread messages for the authenticated user in a group context.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the success status and the count of unread messages.
     * @throws \Throwable If an exception occurs during the message retrieval process.
     */
    public function countMessagesNotReadOfGroup()
    {
        try {
            $unreadMessages = $this->messageQueryGroupService->countMessageNotReads(Auth::user()->id);
            return response()->json(['success' => true, 'unreadMessages' => $unreadMessages], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
