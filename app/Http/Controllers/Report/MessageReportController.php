<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Interfaces\Report\IMessageReport;
use Illuminate\Http\Request;

class MessageReportController extends Controller
{
    public $messageReportService;
    public function __construct(IMessageReport $messageReportService)
    {
        $this->messageReportService = $messageReportService;
    }

    /**
     * Get messages per day for all users.
     *
     * @return array|null The messages per day for all users.
     * @throws \Throwable If an exception occurs during the process.
     */
    public function getMessagesPerDayAllUsers()
    {
        try {
            $message = $this->messageReportService->getMessagesPerDayAllUsers();
            return response()->json(['success' => true, 'messages' => $message], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()], 500);
        }
    }
}
