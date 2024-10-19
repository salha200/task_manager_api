<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function create(TaskCreateRequest $request): JsonResponse
    {
        try {
            $task = $this->taskService->createTask($request->validated());
            return response()->json($task, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create task.'], 500);
        }
    }

    public function updateStatus(TaskUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $task = $this->taskService->updateStatus($id, $request->validated());
            return response()->json($task, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update task status.'], 500);
        }
    }

    public function reassign(Request $request, int $id): JsonResponse
    {
        $request->validate(['assigned_to' => 'required|exists:users,id']);

        try {
            $task = $this->taskService->reassignTask($id, $request->assigned_to);
            return response()->json($task, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to reassign task.'], 500);
        }
    }

    public function addComment(Request $request, int $id): JsonResponse
    {
        $request->validate(['comment' => 'required|string']);

        try {
            $comment = $this->taskService->addComment($id, $request->comment, auth()->id());
            return response()->json($comment, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add comment.'], 500);
        }
    }

    public function addAttachment(Request $request, int $id): JsonResponse
    {
        $request->validate(['attachment' => 'required|file']);

        try {
            $attachment = $this->taskService->addAttachment($id, $request->file('attachment'), auth()->id());
            return response()->json($attachment, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add attachment.'], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $task = Cache::remember("task_{$id}", 60, function () use ($id) {
                return $this->taskService->getTask($id);
            });
            return response()->json($task, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Task not found.'], 404);
        }
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $tasks = Cache::remember('tasks_index', 60, function () use ($request) {
                return $this->taskService->getAllTasks($request->all());
            });
            return response()->json($tasks, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch tasks.'], 500);
        }
    }

    public function assign(Request $request, int $id): JsonResponse
    {
        $request->validate(['assigned_to' => 'required|exists:users,id']);

        try {
            $task = $this->taskService->assignTask($id, $request->assigned_to);
            return response()->json($task, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to assign task.'], 500);
        }
    }

    public function dailyTasks(): JsonResponse
    {
        try {
            $tasks = Cache::remember('daily_tasks', 60, function () {
                return $this->taskService->getDailyTasks();
            });
            return response()->json($tasks, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch daily tasks.'], 500);
        }
    }

    public function blockedTasks(): JsonResponse
    {
        try {
            $tasks = Cache::remember('blocked_tasks', 60, function () {
                return $this->taskService->getBlockedTasks();
            });
            return response()->json($tasks, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch blocked tasks.'], 500);
        }
    }
}
