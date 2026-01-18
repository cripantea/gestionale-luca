<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function index(Request $request): Response
    {
        $showCompleted = $request->boolean('show_completed', false);
        
        $query = Task::with(['project.contact', 'project.projectType']);
        
        if (!$showCompleted) {
            $query->whereIn('status', ['da_fare', 'in_corso', 'in_pausa', 'in_attesa']);
        }
        
        $tasks = $query->orderByDeadline()->get();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'showCompleted' => $showCompleted,
        ]);
    }

    public function create(): Response
    {
        $clients = \App\Models\Contact::withCount('projects')
            ->orderBy('name')
            ->get();
        
        $projects = Project::with('contact')->orderBy('name')->get();

        return Inertia::render('Tasks/Create', [
            'clients' => $clients,
            'projects' => $projects,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'blocked_by_task_id' => 'nullable|exists:tasks,id',
            'priority' => 'nullable|in:bassa,media,alta,critica',
            'estimated_hours' => 'nullable|numeric|min:0',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'nullable|in:daily,weekly,monthly,yearly',
            'recurrence_interval' => 'nullable|integer|min:1',
            'recurrence_end_date' => 'nullable|date|after:deadline',
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task creata con successo.');
    }

    public function show(Task $task): Response
    {
        $task->load(['project.contact', 'project.projectType', 'blockedBy', 'blockingTasks']);

        return Inertia::render('Tasks/Show', [
            'task' => $task,
        ]);
    }

    public function edit(Task $task): Response
    {
        $projects = Project::with('contact')->orderBy('name')->get();
        $availableTasks = Task::where('project_id', $task->project_id)
            ->where('id', '!=', $task->id)
            ->get();

        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'projects' => $projects,
            'availableTasks' => $availableTasks,
        ]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'status' => 'required|in:da_fare,in_corso,completato,in_pausa,in_attesa',
            'blocked_by_task_id' => 'nullable|exists:tasks,id',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task aggiornata con successo.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task eliminata con successo.');
    }

    // Metodi per gestire gli stati delle task
    public function start(Task $task): RedirectResponse
    {
        $task->start();

        return back()->with('success', 'Task avviata.');
    }

    public function complete(Task $task): RedirectResponse
    {
        $task->complete();
        
        // Se la task è ricorrente, crea automaticamente la prossima istanza
        if ($task->is_recurring) {
            $task->createNextRecurrence();
        }

        return back()->with('success', 'Task completata.' . ($task->is_recurring ? ' Prossima ricorrenza creata.' : ''));
    }

    public function pause(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'blocked_by_task_id' => 'nullable|exists:tasks,id',
        ]);

        $task->pause($validated['blocked_by_task_id'] ?? null);

        return back()->with('success', 'Task messa in pausa.');
    }

    public function resume(Task $task): RedirectResponse
    {
        $task->resume();

        return back()->with('success', 'Task ripresa.');
    }

    public function batchDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:tasks,id'
        ]);

        $count = Task::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('tasks.index')
            ->with('success', "{$count} task eliminate con successo.");
    }
}
