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
            $seconds = ConvertTime::convertToSeconds($request->conversion_type, $request->amount);
            return $this->userActivity->exportUserActivityGeneral($seconds);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
