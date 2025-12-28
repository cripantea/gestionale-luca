<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'contact_id',
        'project_type_id',
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function projectType(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function generateTasksFromTemplates(): void
    {
        $templates = $this->projectType->taskTemplates;

        foreach ($templates as $template) {
            Task::create([
                'project_id' => $this->id,
                'task_template_id' => $template->id,
                'name' => $template->name,
                'description' => $template->description,
                'deadline' => now()->addDays($template->default_deadline_days),
                'status' => 'da_fare',
            ]);
        }
    }
}
