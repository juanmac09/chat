<?php

namespace App\Http\Controllers\Reports;

use App\Helpers\ConvertTime;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\InactiveReportRequest;
use App\Interfaces\Report\IGroupReport;
use App\Interfaces\Report\IGroupUserReport;

class GroupReportController extends Controller
{
    public $groupReportService;
    public $groupUserReportService;
    public function __construct(IGroupReport $groupReportService,IGroupUserReport $groupUserReportService)
    {
        $this->groupReportService = $groupReportService;
        $this->groupUserReportService = $groupUserReportService;
    }
    /**
     * Get inactive groups based on the specified time duration.
     *
     * @param \App\Http\Requests\Report\InactiveReportRequest $request The request containing the conversion type and amount.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the success status and the list of inactive groups.
     *
     * @throws \Throwable If an exception occurs during the process.
     */
    public function getInactiveGroups(InactiveReportRequest $request)
    {
        try {
            $seconds = ConvertTime::convertToSeconds($request->conversion_type, $request->amount);
            $groups = $this->groupReportService->getInactiveGroups($seconds);
            return response()->json(['success' => true, 'groups' => $groups], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Get the number of users per group.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the success status and the list of groups with their respective number of users.
     *
     * @throws \Throwable If an exception occurs during the process.
     */
    public function getNumberOfUsersPerGroup()
    {
        try {
            $groups = $this->groupUserReportService->getNumberOfUsersPerGroup();
            return response()->json(['success' => true, 'groups' => $groups], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
