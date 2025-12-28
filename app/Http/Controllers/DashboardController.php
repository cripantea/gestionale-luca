<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $tasks = Task::with(['project.contact', 'project.projectType'])
            ->orderByDeadline()
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->name,
                    'description' => $task->description,
                    'status' => $task->status,
                    'deadline' => $task->deadline,
                    'is_overdue' => $task->isOverdue(),
                    'is_due_today' => $task->isDueToday(),
                    'total_work_time' => $task->total_work_time,
                    'project' => [
                        'id' => $task->project->id,
                        'name' => $task->project->name,
                        'contact_id' => $task->project->contact->id,
                        'contact_name' => $task->project->contact->name,
                        'project_type_name' => $task->project->projectType->name,
                    ],
                ];
            });

        return Inertia::render('Dashboard', [
            'tasks' => $tasks,
        ]);
    }
}

