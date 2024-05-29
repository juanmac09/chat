<?php

namespace App\Http\Controllers\Exports;

use App\Helpers\ConvertTime;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exports\UserActivityRequest;
use App\Interfaces\Exports\IReportGeneralExport;
use Illuminate\Http\Request;

class ReportGeneralController extends Controller
{
    public $reportGeneralService;
    public function __construct(IReportGeneralExport $reportGeneralService ) {
        $this->reportGeneralService = $reportGeneralService;
    }

    public function ReportGeneralExport(UserActivityRequest $request){
        try {
            $date = ConvertTime::calculateTime($request->amount, $request->conversion_type);
            $seconds = ConvertTime::convertToSeconds($request->conversion_type, $request->amount);
            return $this -> reportGeneralService -> ReportGeneralExport($seconds);
        } catch (\Throwable $th) {
            return response()->json(['success' => false,'error' => $th -> getMessage()], 500);
        }
    }
}
