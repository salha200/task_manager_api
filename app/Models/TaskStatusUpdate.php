<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatusUpdate extends Model
{
    protected $fillable = ['task_id', 'old_status', 'new_status', 'updated_by'];

    // علاقة مع موديل Task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // علاقة مع موديل User (المستخدم الذي قام بالتحديث)
    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

