<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\CreateGroupRequest;
use App\Http\Requests\Group\DeleteGroupRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Interfaces\IGroupManagement;

class GroupManagementController extends Controller
{
    public $group_service;

    public function __construct(IGroupManagement $group_service)
    {
        $this->group_service = $group_service;
    }
    /**
     * Create a new group.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function create_group(CreateGroupRequest $request)
    {
        try {
            $this->group_service->create_group($request->name, $request->participants);
            return response()->json(['success' => 'true'], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => 'true', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Get all groups.
     *
     * This method retrieves all the groups from the group service.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the list of groups.
     * @throws \Throwable If an exception occurs while retrieving the groups.
     */
    public function get_groups()
    {
        try {
            $groups = $this->group_service->get_groups();
            return response()->json(['success' => 'true', 'groups' => $groups], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => 'true', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Update a group.
     *
     * This method updates a group with the provided ID and new name.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success of the operation.
     * @throws \Throwable If an exception occurs while updating the group.
     */
    public function update_group(UpdateGroupRequest $request)
    {
        try {
            $this->group_service->update_group($request->id, $request->only('name'));
            return response()->json(['success' => 'true'], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => 'false', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Delete a group.
     *
     * This method deletes a group with the provided ID.
     *
     * @param  \Illuminate\Http\Request  $request The request containing the ID of the group to be deleted.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the success of the operation.
     * @throws \Throwable If an exception occurs while deleting the group.
     */
    public function delete_group(DeleteGroupRequest $request)
    {
        try {
            $this->group_service->delete_group($request->id);
            return response()->json(['success' => 'true'], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => 'false', 'error' => $th->getMessage()], 500);
        }
    }
}
