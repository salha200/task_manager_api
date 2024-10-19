<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',            // تعديل اسم الحقل
        'description',      // تعديل اسم الحقل
        'type',             // تعديل اسم الحقل
        'status',           // تعديل اسم الحقل
        'priority',         // تعديل اسم الحقل
        'due_date',         // تعديل اسم الحقل
        'assigned_to',      // تعديل اسم الحقل
    ];

    /**
     * @return BelongsTo
     */
    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to'); // تعديل اسم العلاقة
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user'); // تأكد من إضافة هذا الحقل في الـ Migration إذا كنت تستخدمه
    }

    public function taskDependencies(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on');
    }

    public function dependents(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'depends_on', 'task_id');
    }

    public function dependent(): HasMany
    {
        return $this->hasMany(TaskDependency::class, 'depends_on');
    }

    public function dependentTasks(): HasMany
    {
        return $this->hasMany(TaskDependency::class, 'task_id');
    }

    /**
     * A task may depend on many other tasks.
     *
     * @return HasMany
     */
    public function dependencies(): HasMany
    {
        return $this->hasMany(TaskDependency::class, 'task_id');
    }

    /**
     * A task may block many other tasks.
     *
     * @return HasMany
     */
    public function blockingTasks(): HasMany
    {
        return $this->hasMany(TaskDependency::class, 'depends_on');
    }

    /**
     * Check if the task is blocked by incomplete dependencies.
     *
     * @return bool
     */
    public function isBlocked(): bool
    {
        // Check if any of the dependent tasks are not completed
        foreach ($this->dependencies as $dependency) {
            if ($dependency->dependency && $dependency->dependency->status !== 'Completed') { // تعديل اسم الحقل
                return true;
            }
        }
        return false;
    }

    /**
     * Update the task status to "Blocked" or "Open" depending on dependencies.
     *
     * @return void
     */
    public function updateStatusBasedOnDependencies(): void
    {
        if ($this->isBlocked()) {
            $this->status = 'Blocked'; // تعديل اسم الحقل
        } else {
            $this->status = 'Open'; // تعديل اسم الحقل
        }
        $this->save();
    }

    /**
     * @return MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * @return HasMany
     */
    public function statusUpdates(): HasMany
    {
        return $this->hasMany(TaskStatusUpdate::class);
    }

    /**
     * @return MorphMany
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function getDueDateAttribute($value): string // تعديل اسم الـ accessor
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    /**
     * @param $value
     * @return void
     */
    public function setDueDateAttribute($value): void // تعديل اسم الـ mutator
    {
        $this->attributes['due_date'] = Carbon::parse($value);
    }

    /**
     * Scope a query to only include tasks of a given type.
     *
     * @param Builder $query
     * @param string $type
     * @return Builder
     */
    public function scopeType(Builder $query, string $type): Builder // تعديل اسم الـ scope
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include tasks with a specific due date.
     *
     * @param Builder $query
     * @param string $dueDate
     * @return Builder
     */
    public function scopeDueDate(Builder $query, string $dueDate): Builder // تعديل اسم الـ scope
    {
        return $query->whereDate('due_date', $dueDate);
    }

    /**
     * Scope a query to only include tasks assigned to a specific user.
     *
     * @param Builder $query
     * @param int $assignedTo
     * @return Builder
     */
    public function scopeAssignedTo(Builder $query, int $assignedTo): Builder // تعديل اسم الـ scope
    {
        return $query->where('assigned_to', $assignedTo);
    }

    /**
     * Scope a query to filter tasks based on dependencies.
     *
     * @param Builder $query
     * @param mixed $dependsOn
     * @return Builder
     */
    public function scopeDependsOn(Builder $query, $dependsOn): Builder
    {
        if (is_string($dependsOn) && strtolower($dependsOn) === 'null') {
            return $query->whereDoesntHave('dependents');
        }

        if (is_numeric($dependsOn)) {
            return $query->whereHas('taskDependencies', function ($q) use ($dependsOn) {
                $q->where('depends_on', $dependsOn);
            });
        }

        return $query;
    }

    /**
     * Scope a query to only include tasks of a given status.
     *
     * @param Builder $query
     * @param string $status
     * @return Builder
     */
    public function scopeStatus(Builder $query, string $status): Builder // تعديل اسم الـ scope
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include tasks of a given priority.
     *
     * @param Builder $query
     * @param string $priority
     * @return Builder
     */
    public function scopePriority(Builder $query, string $priority): Builder // تعديل اسم الـ scope
    {
        return $query->where('priority', $priority);
    }
}
