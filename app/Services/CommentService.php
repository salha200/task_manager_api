<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Comment;
use Illuminate\Support\Facades\Cache;
use Exception;

class CommentService
{
    public function addComment($taskId, $data)
    {
        try {
            // Find the task or fail
            $task = Task::findOrFail($taskId);

            // Create a new comment
            $comment = $task->comments()->create([
                'content' => $data['content'],
                'user_id' => auth()->id(),
            ]);

            // Optionally, clear the cache for the task comments if caching is implemented
            Cache::forget('task_comments_' . $taskId);

            return $comment;
        } catch (Exception $e) {
            // Handle the exception (logging, rethrowing, etc.)
            throw new Exception('Failed to add comment: ' . $e->getMessage());
        }
    }

    public function getCommentsForTask($taskId)
    {
        // Optionally cache the comments
        return Cache::remember('task_comments_' . $taskId, 60, function () use ($taskId) {
            return Task::findOrFail($taskId)->comments()->get();
        });
    }
}
