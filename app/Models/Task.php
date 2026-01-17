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
        // Task ricorrenti
        'is_recurring',
        'recurrence_pattern',
        'recurrence_interval',
        'recurrence_end_date',
        'parent_recurring_task_id',
        // Priorità e stima
        'priority',
        'priority_score',
        'estimated_hours',
        // Tag e categorie
        'tags',
        'category',
    ];

    protected $casts = [
        'status' => 'string',
        'deadline' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'paused_at' => 'datetime',
        'total_work_time' => 'integer',
        'is_recurring' => 'boolean',
        'recurrence_end_date' => 'date',
        'priority_score' => 'integer',
        'estimated_hours' => 'integer',
        'tags' => 'array',
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

    // Relazione per task ricorrente parent
    public function parentRecurringTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_recurring_task_id');
    }

    // Relazione per task generate da questa ricorrente
    public function childRecurringTasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class, 'parent_recurring_task_id');
    }

    /**
     * Calcola il priority score in base a vari fattori
     * Score: 0-100 (0 = minima priorità, 100 = massima priorità)
     */
    public function calculatePriorityScore(): int
    {
        $score = 50; // Base score

        // Fattore 1: Deadline (max 30 punti)
        if ($this->deadline) {
            $daysUntilDeadline = now()->diffInDays($this->deadline, false);
            if ($daysUntilDeadline < 0) {
                $score += 30; // Task scaduta
            } elseif ($daysUntilDeadline == 0) {
                $score += 25; // Scade oggi
            } elseif ($daysUntilDeadline <= 1) {
                $score += 20; // Scade domani
            } elseif ($daysUntilDeadline <= 3) {
                $score += 15; // Scade tra 2-3 giorni
            } elseif ($daysUntilDeadline <= 7) {
                $score += 10; // Scade questa settimana
            }
        }

        // Fattore 2: Priorità manuale (max 25 punti)
        $priorityPoints = [
            'bassa' => 0,
            'media' => 8,
            'alta' => 18,
            'critica' => 25,
        ];
        $score += $priorityPoints[$this->priority] ?? 0;

        // Fattore 3: Task che bloccano altre (max 15 punti)
        $blockingCount = $this->blockingTasks()->count();
        $score += min($blockingCount * 5, 15);

        // Fattore 4: Stato (max 10 punti)
        if ($this->status === 'in_corso') {
            $score += 10; // Task già iniziate hanno priorità
        } elseif ($this->status === 'in_pausa') {
            $score += 5;
        }

        // Fattore 5: Tempo stimato (max 10 punti - task veloci)
        if ($this->estimated_hours) {
            if ($this->estimated_hours <= 1) {
                $score += 10; // Quick wins
            } elseif ($this->estimated_hours <= 3) {
                $score += 5;
            }
        }

        // Fattore 6: Tag urgente (max 10 punti)
        if ($this->tags && in_array('urgente', $this->tags)) {
            $score += 10;
        }

        return min(max($score, 0), 100); // Clamp tra 0 e 100
    }

    /**
     * Aggiorna il priority score
     */
    public function updatePriorityScore(): void
    {
        $this->update(['priority_score' => $this->calculatePriorityScore()]);
    }

    /**
     * Scope per ordinare per priority score
     */
    public function scopeOrderByPriority(Builder $query): Builder
    {
        return $query->orderByDesc('priority_score');
    }

    /**
     * Crea la prossima istanza di una task ricorrente
     */
    public function createNextRecurrence(): ?Task
    {
        if (!$this->is_recurring) {
            return null;
        }

        // Calcola la prossima deadline (usando copy() per non mutare l'originale!)
        $nextDeadline = match ($this->recurrence_pattern) {
            'daily' => $this->deadline->copy()->addDays($this->recurrence_interval),
            'weekly' => $this->deadline->copy()->addWeeks($this->recurrence_interval),
            'monthly' => $this->deadline->copy()->addMonths($this->recurrence_interval),
            'yearly' => $this->deadline->copy()->addYears($this->recurrence_interval),
            default => null,
        };

        if (!$nextDeadline) {
            return null;
        }

        // Controlla se siamo oltre la data di fine ricorrenza
        if ($this->recurrence_end_date && $nextDeadline->isAfter($this->recurrence_end_date)) {
            return null;
        }

        // Crea la nuova task
        $newTask = $this->replicate();
        $newTask->deadline = $nextDeadline;
        $newTask->status = 'da_fare';
        $newTask->started_at = null;
        $newTask->completed_at = null;
        $newTask->paused_at = null;
        $newTask->total_work_time = 0;
        $newTask->parent_recurring_task_id = $this->id;
        $newTask->save();

        return $newTask;
    }
}
