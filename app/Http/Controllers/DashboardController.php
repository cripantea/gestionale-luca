<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Contact;
use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        // Task ordinate per scadenza
        $tasks = Task::with(['project.contact', 'project.projectType'])
            ->whereIn('status', ['da_fare', 'in_corso', 'in_pausa', 'in_attesa'])
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

        // Statistiche generali
        $stats = [
            'total_contacts' => Contact::count(),
            'total_clients' => Contact::where('status', 'cliente')->count(),
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'attivo')->count(),
            'tasks_completed_this_month' => Task::where('status', 'completato')
                ->whereMonth('completed_at', now()->month)
                ->whereYear('completed_at', now()->year)
                ->count(),
        ];

        // Progetti attivi con progress
        $activeProjects = Project::with(['contact', 'projectType'])
            ->where('status', 'attivo')
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => function ($query) {
                    $query->where('status', 'completato');
                }
            ])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($project) {
                $progress = $project->tasks_count > 0 
                    ? round(($project->completed_tasks_count / $project->tasks_count) * 100)
                    : 0;
                
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'contact_name' => $project->contact->name,
                    'project_type_name' => $project->projectType->name,
                    'tasks_count' => $project->tasks_count,
                    'completed_tasks_count' => $project->completed_tasks_count,
                    'progress' => $progress,
                ];
            });

        // Task urgenti (scadute o in scadenza oggi, non completate)
        $urgentTasks = $tasks->filter(function ($task) {
            return $task['is_overdue'] || $task['is_due_today'];
        })->take(5);

        return Inertia::render('Dashboard', [
            'tasks' => $tasks,
            'urgentTasks' => $urgentTasks->values(),
            'stats' => $stats,
            'activeProjects' => $activeProjects,
        ]);
    }
}

