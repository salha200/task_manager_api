<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class AttachmentService
{
    public function addAttachment($taskId, $file)
    {
        $task = Task::findOrFail($taskId);

        // تخزين المرفق في مجلد attachments باستخدام التخزين المحلي
        $path = $file->store('attachments', 'public');

        // إنشاء سجل للمرفق المرتبط بالمهمة
        $attachment = $task->attachments()->create([
            'path' => $path,
            'user_id' => auth()->id(),
        ]);

        return $attachment;
    }
}
