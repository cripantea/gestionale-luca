<?php

namespace App\Http\Controllers;

use App\Models\ProjectType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectTypeController extends Controller
{
    public function index(): Response
    {
        $projectTypes = ProjectType::withCount(['projects', 'taskTemplates'])
            ->latest()
            ->get();

        return Inertia::render('ProjectTypes/Index', [
            'projectTypes' => $projectTypes,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('ProjectTypes/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ProjectType::create($validated);

        return redirect()->route('project-types.index')
            ->with('success', 'Tipo di progetto creato con successo.');
    }

    public function show(ProjectType $projectType): Response
    {
        $projectType->load(['taskTemplates' => function ($query) {
            $query->orderBy('order');
        }, 'projects.contact']);

        return Inertia::render('ProjectTypes/Show', [
            'projectType' => $projectType,
        ]);
    }

    public function edit(ProjectType $projectType): Response
    {
        return Inertia::render('ProjectTypes/Edit', [
            'projectType' => $projectType,
        ]);
    }

    public function update(Request $request, ProjectType $projectType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $projectType->update($validated);

        return redirect()->route('project-types.index')
            ->with('success', 'Tipo di progetto aggiornato con successo.');
    }

    public function destroy(ProjectType $projectType): RedirectResponse
    {
        $projectType->delete();

        return redirect()->route('project-types.index')
            ->with('success', 'Tipo di progetto eliminato con successo.');
    }
}
