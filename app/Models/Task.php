<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'task_template_id',
        'blocked_by_task_id',
        'name',
        'description',
        'status',
        'deadline',
        'started_at',
        'completed_at',
        'paused_at',
        'total_work_time',
    ];

    protected $casts = [
        'status' => 'string',
        'deadline' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'paused_at' => 'datetime',
        'total_work_time' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function taskTemplate(): BelongsTo
    {
        return $this->belongsTo(TaskTemplate::class);
    }

    public function blockedBy(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'blocked_by_task_id');
    }

    public function blockingTasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class, 'blocked_by_task_id');
    }

    // Scope per ordinare per scadenza
    public function scopeOrderByDeadline(Builder $query): Builder
    {
        $now = now()->toDateTimeString();
        $today = now()->toDateString();

        return $query->orderByRaw("
            CASE
                WHEN deadline < ? THEN 0
                WHEN DATE(deadline) = ? THEN 1
                ELSE 2
            END,
            deadline ASC
        ", [$now, $today]);
    }

    // Scope per task scadute
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('deadline', '<', now())
            ->whereNotIn('status', ['completato']);
    }

    // Scope per task di oggi
    public function scopeDueToday(Builder $query): Builder
    {
        return $query->whereDate('deadline', today())
            ->whereNotIn('status', ['completato']);
    }

    // Metodi per gestire gli stati
    public function start(): void
    {
        $this->update([
            'status' => 'in_corso',
            'started_at' => now(),
        ]);
    }

    public function complete(): void
    {
        if ($this->started_at) {
            $workTime = now()->diffInMinutes($this->started_at);
            $this->total_work_time += $workTime;
        }

        $this->update([
            'status' => 'completato',
            'completed_at' => now(),
        ]);
    }

    public function pause(?int $blockedByTaskId = null): void
    {
        if ($this->started_at && !$this->paused_at) {
            $workTime = now()->diffInMinutes($this->started_at);
            $this->total_work_time += $workTime;
        }

        $this->update([
            'status' => $blockedByTaskId ? 'in_attesa' : 'in_pausa',
            'paused_at' => now(),
            'blocked_by_task_id' => $blockedByTaskId,
        ]);
    }

    public function resume(): void
    {
        $this->update([
            'status' => 'in_corso',
            'started_at' => now(),
            'paused_at' => null,
        ]);
    }

    public function isOverdue(): bool
    {
        return $this->deadline && $this->deadline->isPast() && $this->status !== 'completato';
    }

    public function isDueToday(): bool
    {
        return $this->deadline && $this->deadline->isToday() && $this->status !== 'completato';
    }
}
