<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\AddParticipantsGroupRequest;
use App\Interfaces\IAdvancedGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvancedGroupController extends Controller
{
    public $group_service;

    public function __construct(IAdvancedGroups $group_service)
    {
        $this->group_service = $group_service;
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
     * Retrieves the groups that a user is a part of.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the user's groups.
     * @throws \Throwable If an exception occurs during the operation.
    */
    public function getGroupsForUser()
    {
        try {
            $user = Auth::user();
            $groups = $this->group_service->getGroupsForUser($user);
            return response()->json(['success' => 'true', 'groups' => $groups], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => 'false', 'error' => $th->getMessage()], 500);
        }
    }
}
