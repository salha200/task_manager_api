<?php

namespace App\Services;
use App\Services\Log;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Exception;

class ReportService
{ public function getDailyTaskReport(): array
    {
        $today = Carbon::today();

        // Fetch tasks created, completed, or updated today
        $tasks = Task::whereDate('created_at', $today)
            ->orWhereDate('updated_at', $today)
            ->orWhereDate('completed_at', $today)
            ->get();

        // Return report with count and task details
        return [
            'date' => $today->toDateString(),
            'tasks_count' => $tasks->count(),
            'tasks' => $tasks
        ];
    }
}
