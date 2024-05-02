<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\AddParticipantsGroupRequest;
use App\Http\Requests\Group\VerifyGroupIdRequest;
use App\Http\Requests\User\VerifyUserIdRequest;
use App\Interfaces\IAdvancedGroups;
use App\Interfaces\IUserRepository;
use App\Interfaces\MessagesInterfaces\IMessageQuery;
use Illuminate\Support\Facades\Auth;

class AdvancedGroupController extends Controller
{
    public $group_service;
    public $user_service;
    public $message_service;
    public function __construct(IAdvancedGroups $group_service, IUserRepository $user_service, IMessageQuery $message_service)
    {
        $this->group_service = $group_service;
        $this->user_service = $user_service;
        $this->message_service = $message_service;
    }

    /**
     * Adds participants to a group.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request containing the participants and group id.
     * @return \Illuminate\Http\JsonResponse  A JSON response containing the result of the operation.
     * @throws \Throwable  If an exception occurs during the operation.
     */
    public function addParticipants(AddParticipantsGroupRequest $request)
    {
        try {
            $response = $this->group_service->addParticipants($request->participants, $request->id);
            return response()->json($response, $response['status']);
        } catch (\Throwable $th) {
            return response()->json(['success' => 'false', 'error' => $th->getMessage()], 500);
        }
    }


    /**
     * Removes participants from a group.
     *
     * @param array $participants An array of participant IDs.
     * @param int $group_id The ID of the group from which participants will be removed.
     *
     * @return array A response array containing the status code, message, and success flag.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the group with the given ID is not found.
     */
    public function removeParticipants(AddParticipantsGroupRequest $request)
    {
        try {
            $response = $this->group_service->removeParticipants($request->participants, $request->id);
            return response()->json($response, $response['status']);
        } catch (\Throwable $th) {
            return response()->json(['success' => 'false', 'error' => $th->getMessage()], 500);
        }
    }


    /**
     * Retrieves the list of groups for a given user.
     *
     * @param VerifyUserIdRequest $request The request containing the user ID.
     *
     * @return JsonResponse A JSON response containing the list of groups and a status code.
     *
     * @throws \Throwable If an error occurs while retrieving the groups.
     */
    public function getGroupsForUser(VerifyUserIdRequest $request)
    {
        try {
            if ($request->filled('id')) {
                $user = $this->user_service->getUserForId($request->id);
            } else {
                $user =  Auth::user();
            }
            $groups = $this->group_service->getGroupsForUser($user);
            $groups = $this->message_service->getLastMessageFromGroup($groups);
            return response()->json(['success' => 'true', 'groups' => $groups], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => 'false', 'error' => $th->getMessage()], 500);
        }
    }


    /**
     * Retrieves the list of participants for a given group.
     *
     * @param VerifyGroupIdRequest $request The request containing the group ID.
     *
     * @return JsonResponse A JSON response containing the list of participants and a status code.
     *
     * @throws \Throwable If an error occurs while retrieving the participants.
     */
    public function getParticipantsForGroup(VerifyGroupIdRequest $request)
    {
        try {
            $participants = $this->group_service->getParticipantsForGroup($request->id);
            return response()->json(['success' => 'true', 'participants' => $participants], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => 'false', 'error' => $th->getMessage()], 500);
        }
    }
}
