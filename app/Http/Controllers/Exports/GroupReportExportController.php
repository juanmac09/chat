<?php

namespace App\Http\Controllers\Exports;

use App\Helpers\ConvertTime;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exports\UserActivityRequest;
use App\Interfaces\Exports\IExportParticipants;
use App\Interfaces\Exports\IGroupActivityExport;
use Illuminate\Http\Request;

class GroupReportExportController extends Controller
{
    public $participantsServices;
    public $groupServices;
    public function __construct(IExportParticipants $participantsServices = null,IGroupActivityExport $groupServices)
    {
        $this->participantsServices = $participantsServices;
        $this->groupServices = $groupServices;
    }
    /**
     * Exports the group participants.
     *
     * @return mixed
     * @throws \Throwable
     */
    public function exportGroupParticipants()
    {
        try {
            return $this->participantsServices->exportGroupParticipants();
        } catch (\Throwable $th) {
            return response()->json(['susccess' => false, 'error' => $th->getMessage()], 500);
        }
    }


    public function GroupActivityExport(UserActivityRequest $request){
        try {
            $date = ConvertTime::calculateTime($request->amount, $request->conversion_type);
            $seconds = ConvertTime::convertToSeconds($request->conversion_type, $request->amount);
            return $this->groupServices->GroupActivityExport($seconds,$date);
        } catch (\Throwable $th) {
            return response()->json(['susccess' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
