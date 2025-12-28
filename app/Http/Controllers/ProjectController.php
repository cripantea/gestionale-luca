<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Contact;
use App\Models\ProjectType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(): Response
    {
        $projects = Project::with(['contact', 'projectType'])
            ->withCount('tasks')
            ->latest()
            ->get();

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
        ]);
    }

    public function create(): Response
    {
        $contacts = Contact::where('status', 'cliente')
            ->orderBy('name')
            ->get();

        $projectTypes = ProjectType::orderBy('name')->get();

        return Inertia::render('Projects/Create', [
            'contacts' => $contacts,
            'projectTypes' => $projectTypes,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'project_type_id' => 'required|exists:project_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:attivo,completato,sospeso',
        ]);

        $project = Project::create($validated);

        // Genera le task dal template
        $project->generateTasksFromTemplates();

        // Promuovi contatto a cliente se non lo è già
        if ($project->contact->status === 'contatto') {
            $project->contact->promoteToCliente();
        }

        return redirect()->route('projects.index')
            ->with('success', 'Progetto creato con successo e task generate.');
    }

    public function show(Project $project): Response
    {
        $project->load(['contact', 'projectType', 'tasks' => function ($query) {
            $query->orderByDeadline();
        }]);

        return Inertia::render('Projects/Show', [
            'project' => $project,
        ]);
    }

    public function edit(Project $project): Response
    {
        $contacts = Contact::where('status', 'cliente')
            ->orderBy('name')
            ->get();

        $projectTypes = ProjectType::orderBy('name')->get();

        return Inertia::render('Projects/Edit', [
            'project' => $project,
            'contacts' => $contacts,
            'projectTypes' => $projectTypes,
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'project_type_id' => 'required|exists:project_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:attivo,completato,sospeso',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Progetto aggiornato con successo.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Progetto eliminato con successo.');
    }
}
