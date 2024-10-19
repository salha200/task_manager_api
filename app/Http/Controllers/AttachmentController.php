<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachmentRequest;
use App\Services\AttachmentService;

class AttachmentController extends Controller
{
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function addAttachment($taskId, AttachmentRequest $request)
    {
        $attachment = $this->attachmentService->addAttachment($taskId, $request->file('file'));

        return response()->json(['message' => 'Attachment added successfully', 'attachment' => $attachment]);
    }
}
