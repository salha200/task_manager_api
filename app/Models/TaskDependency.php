<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDependency extends Model
{
           protected $fillable = ['task_id', 'depends_on'];

        // علاقة مع المهمة التي تعتمد على مهمة أخرى
        public function task()
        {
            return $this->belongsTo(Task::class, 'task_id');
        }

        // علاقة مع المهمة المعتمد عليها
        public function dependentTask()
        {
            return $this->belongsTo(Task::class, 'depends_on');
        }
    }


