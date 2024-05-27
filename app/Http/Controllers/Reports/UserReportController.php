<?php

namespace App\Http\Controllers\Reports;

use App\Helpers\ConvertTime;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\InactiveReportRequest;
use App\Interfaces\Report\IUserReport;

class UserReportController extends Controller
{

    public $userReportService;

    public function __construct(IUserReport $userReportService)
    {
        $this->userReportService = $userReportService;
    }

    /**
     * Retrieves the list of inactive users based on the specified time duration.
     *
     * @param \App\Http\Requests\Report\InactiveReportRequest $request The request containing the conversion type and amount.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the list of inactive users and a success flag.
     * @throws \Throwable If an exception occurs during the process.
     */
    public function getGeneralInactiveUsers(InactiveReportRequest $request)
    {
        try {
            $seconds = ConvertTime::convertToSeconds($request->conversion_type, $request->amount);
            $users = $this->userReportService->getGeneralInactiveUsers($seconds);
            return response()->json(['success' => true, 'users' => $users], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Retrieves the list of specific inactive users based on the specified time duration and recipient type.
     *
     * @param \App\Http\Requests\Report\InactiveReportRequest $request The request containing the conversion type, amount, and recipient type.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the list of specific inactive users and a success flag.
     * @throws \Throwable If an exception occurs during the process.
     */
    public function getSpecificInactiveUsers(InactiveReportRequest $request)
    {
        try {
            $seconds = ConvertTime::convertToSeconds($request->conversion_type, $request->amount);
            $users = $this->userReportService->getSpecificInactiveUsers($seconds, $request->recipient_type);
            return response()->json(['success' => true, 'users' => $users], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Retrieves the list of general active users based on the specified time duration.
     *
     * @param \App\Http\Requests\Report\InactiveReportRequest $request The request containing the conversion type and amount.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the list of general active users and a success flag.
     * @throws \Throwable If an exception occurs during the process.
     */
    public function getGeneralActiveUsers(InactiveReportRequest $request)
    {
        try {
            $seconds = ConvertTime::convertToSeconds($request->conversion_type, $request->amount);
            $users = $this->userReportService->getGeneralActiveUsers($seconds);
            return response()->json(['success' => true, 'users' => $users], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Retrieves the list of specific active users based on the specified time duration and recipient type.
     *
     * @param \App\Http\Requests\Report\InactiveReportRequest $request The request containing the conversion type, amount, and recipient type.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the list of specific active users and a success flag.
     * @throws \Throwable If an exception occurs during the process.
 */
    public function getSpecificActiveUsers(InactiveReportRequest $request)
    {
        try {
            $seconds = ConvertTime::convertToSeconds($request->conversion_type, $request->amount);
            $users = $this->userReportService->getSpecificActiveUsers($seconds, $request->recipient_type);
            return response()->json(['success' => true, 'users' => $users], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
