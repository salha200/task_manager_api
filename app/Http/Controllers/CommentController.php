<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    // إضافة تعليق على مهمة
    public function addComment($taskId, Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $this->commentService->addComment($taskId, $data);
        return response()->json(['comment' => $comment], 201);
    }

    public function getComments($taskId)
    {
        $comments = $this->commentService->getCommentsForTask($taskId);
        return response()->json(['comments' => $comments]);
    }

}
