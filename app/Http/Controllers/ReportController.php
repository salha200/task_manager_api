<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\DailyTaskReportRequest;
class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function dailyTaskReport(DailyTaskReportRequest $request): JsonResponse
    {
        try {
            $report = $this->reportService->getDailyTaskReport();
            return response()->json($report, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate daily task report.'], 500);
        }
    }
}
