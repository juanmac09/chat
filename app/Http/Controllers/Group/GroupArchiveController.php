<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Interfaces\ArchiveGroups\IGetArchivedGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupArchiveController extends Controller
{
    public $groupService;

    public function __construct(IGetArchivedGroups $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * Retrieves the archived groups.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroupsFiledGeneral()
    {
        try {
            $groups = $this->groupService->getGroupsFiledGeneral();
            return response()->json(['success' => 'true', 'groups' => $groups], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Retrieves the archived groups for a specific user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArchivedGroupsForAUser()
    {
        try {
            $groups = $this->groupService->getArchivedGroupsForAUser(Auth::user());
            return response()->json(['success' => 'true', 'groups' => $groups], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
