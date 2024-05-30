<?php

namespace App\Http\Controllers\Exports;

use App\Helpers\ConvertTime;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exports\UserActivityRequest;
use App\Interfaces\Exports\IUserActivity;
use Illuminate\Http\Request;

class UserReportExportController extends Controller
{
    public $userActivity;
    public function __construct(IUserActivity $userActivity)
    {
        $this->userActivity = $userActivity;
    }

    /**
     * Exports the general user activity data.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request containing the user activity data.
     * @return \Illuminate\Http\JsonResponse  A JSON response containing the exported user activity data.
     * @throws \Throwable  If an exception occurs during the export process.
     */
    public function exportUserActivityGeneral(UserActivityRequest $request)
    {
        try {
            $date = ConvertTime::calculateTime($request->amount, $request->conversion_type);
            $seconds = ConvertTime::convertToSeconds($request->conversion_type, $request->amount);
            return $this->userActivity->exportUserActivityGeneral($seconds, $date);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Exports the specific user activity data.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request containing the specific user activity data.
     * @return \Illuminate\Http\JsonResponse  A JSON response containing the exported specific user activity data.
     * @throws \Throwable  If an exception occurs during the export process.
     */
    public function exportUserActivitySpecific(UserActivityRequest $request)
    {
        try {
            $date = ConvertTime::calculateTime($request->amount, $request->conversion_type);
            $seconds = ConvertTime::convertToSeconds($request->conversion_type, $request->amount);
            return $this->userActivity->exportUserActivitySpecific($seconds, $request->recipient_type, $date);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
