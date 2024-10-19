<?php

namespace App\Services;

use App\Models\Task;

class TaskDependencyService
{
    public function getBlockedTasks()
    {
        // استرجاع جميع المهام التي حالتها "Blocked"
        $tasks = Task::where('status', 'Blocked')->get();
        return $tasks;
    }
}
