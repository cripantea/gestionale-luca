<?php

namespace App\Http\Controllers;

use App\Models\TaskTemplate;
use App\Models\ProjectType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskTemplateController extends Controller
{
    public function index(): Response
    {
        $taskTemplates = TaskTemplate::with('projectType')
            ->orderBy('project_type_id')
            ->orderBy('order')
            ->get();

        return Inertia::render('TaskTemplates/Index', [
            'taskTemplates' => $taskTemplates,
        ]);
    }

    public function create(): Response
    {
        $projectTypes = ProjectType::orderBy('name')->get();

        return Inertia::render('TaskTemplates/Create', [
            'projectTypes' => $projectTypes,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_type_id' => 'required|exists:project_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_deadline_days' => 'required|integer|min:1',
            'order' => 'required|integer|min:0',
        ]);

        TaskTemplate::create($validated);

        return redirect()->route('task-templates.index')
            ->with('success', 'Template task creato con successo.');
    }

    public function show(TaskTemplate $taskTemplate): Response
    {
        $taskTemplate->load('projectType');

        return Inertia::render('TaskTemplates/Show', [
            'taskTemplate' => $taskTemplate,
        ]);
    }

    public function edit(TaskTemplate $taskTemplate): Response
    {
        $projectTypes = ProjectType::orderBy('name')->get();

        return Inertia::render('TaskTemplates/Edit', [
            'taskTemplate' => $taskTemplate,
            'projectTypes' => $projectTypes,
        ]);
    }

    public function update(Request $request, TaskTemplate $taskTemplate): RedirectResponse
    {
        $validated = $request->validate([
            'project_type_id' => 'required|exists:project_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_deadline_days' => 'required|integer|min:1',
            'order' => 'required|integer|min:0',
        ]);

        $taskTemplate->update($validated);

        return redirect()->route('task-templates.index')
            ->with('success', 'Template task aggiornato con successo.');
    }

    public function destroy(TaskTemplate $taskTemplate): RedirectResponse
    {
        $taskTemplate->delete();

        return redirect()->route('task-templates.index')
            ->with('success', 'Template task eliminato con successo.');
    }
}

