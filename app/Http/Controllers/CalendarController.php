<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use Inertia\Response;

class CalendarController extends Controller
{
    public function index(): Response
    {
        $tasks = Task::with(['project.contact', 'project.projectType'])
            ->whereNotNull('deadline')
            ->whereIn('status', ['da_fare', 'in_corso', 'in_pausa', 'in_attesa'])
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->name,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'deadline' => $task->deadline->toDateTimeString(),
                    'project' => [
                        'name' => $task->project->name,
                        'client_name' => $task->project->contact->name,
                    ],
                ];
            });

        return Inertia::render('Calendar/Index', [
            'tasks' => $tasks,
        ]);
    }
}
