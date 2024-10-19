<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class TaskService
{
    public function createTask(array $data)
    {
        return Task::create($data);
    }

    public function updateStatus(int $id, array $data)
    {
        $task = Task::findOrFail($id);
        $task->status = $data['status'];
        $task->save();
        return $task;
    }

    public function reassignTask(int $id, int $userId)
    {
        $task = Task::findOrFail($id);
        $task->assigned_to = $userId;
        $task->save();
        return $task;
    }

    public function addComment(int $id, string $comment, int $userId)
    {
        $task = Task::findOrFail($id);
        return $task->comments()->create([
            'comment' => $comment,
            'user_id' => $userId,
        ]);
    }

    public function addAttachment(int $id, $attachment, int $userId)
    {
        $task = Task::findOrFail($id);
        $path = $attachment->store('attachments');
        return $task->attachments()->create([
            'path' => $path,
            'user_id' => $userId,
        ]);
    }

    public function getTask(int $id)
    {
        return Task::with(['assignedToUser', 'createdByUser', 'comments', 'attachments'])->findOrFail($id);
    }

    public function getAllTasks(array $filters)
    {
        $tasks = Task::query();

        // تطبيق الفلاتر
        if (isset($filters['type'])) {
            $tasks->where('type', $filters['type']);
        }

        if (isset($filters['status'])) {
            $tasks->where('status', $filters['status']);
        }

        if (isset($filters['assigned_to'])) {
            $tasks->where('assigned_to', $filters['assigned_to']);
        }

        if (isset($filters['due_date'])) {
            $tasks->whereDate('due_date', $filters['due_date']);
        }

        if (isset($filters['priority'])) {
            $tasks->where('priority', $filters['priority']);
        }

        if (isset($filters['depends_on']) && $filters['depends_on'] === 'null') {
            $tasks->whereDoesntHave('dependencies');
        } elseif (isset($filters['depends_on'])) {
            $tasks->whereHas('dependencies', function ($query) use ($filters) {
                $query->where('depends_on', $filters['depends_on']);
            });
        }

        return $tasks->get();
    }

    public function assignTask(int $id, int $userId)
    {
        $task = Task::findOrFail($id);
        $task->assigned_to = $userId;
        $task->save();
        return $task;
    }

    public function getDailyTasks()
    {
        return Task::whereDate('created_at', today())->get();
    }

    public function getBlockedTasks()
    {
        return Task::where('status', 'Blocked')->get();
    }
}
