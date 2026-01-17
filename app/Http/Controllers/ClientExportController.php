<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Response;

class ClientExportController extends Controller
{
    /**
     * Export singolo cliente in JSON
     */
    public function exportClient(Contact $client): Response
    {
        $client->load(['projects.tasks', 'projects.projectType']);

        $data = [
            'client_info' => [
                'name' => $client->name,
                'company' => $client->company,
                'email' => $client->email,
                'phone' => $client->phone,
                'referente_principale' => $client->referente_principale,
                'settore_business' => $client->settore_business,
                'sito_web' => $client->sito_web,
            ],
            'financial' => [
                'accordo_economico_mensile' => $client->accordo_economico_mensile,
                'dettagli_accordo' => $client->dettagli_accordo,
                'potenziale_upsell' => $client->potenziale_upsell,
                'note_upsell' => $client->note_upsell,
            ],
            'satisfaction' => [
                'livello_soddisfazione' => $client->livello_soddisfazione,
                'nps_score' => $client->nps_score,
                'data_acquisizione' => $client->data_acquisizione?->format('Y-m-d'),
            ],
            'projects' => $client->projects->map(function ($project) {
                return [
                    'name' => $project->name,
                    'type' => $project->projectType->name,
                    'status' => $project->status,
                    'tasks_count' => $project->tasks->count(),
                    'completed_tasks' => $project->tasks->where('status', 'completato')->count(),
                ];
            }),
            'totals' => [
                'total_projects' => $client->projects->count(),
                'total_tasks' => $client->projects->sum(fn($p) => $p->tasks->count()),
                'completed_tasks' => $client->projects->sum(fn($p) => $p->tasks->where('status', 'completato')->count()),
            ],
            'export_date' => now()->toDateTimeString(),
        ];

        $filename = 'cliente_' . str_replace(' ', '_', strtolower($client->name)) . '_' . now()->format('Y-m-d') . '.json';

        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export tutti i clienti in JSON
     */
    public function exportAll(): Response
    {
        $clients = Contact::withCount(['projects'])
            ->with(['projects' => function ($query) {
                $query->withCount('tasks');
            }])
            ->get();

        $data = [
            'export_info' => [
                'total_clients' => $clients->count(),
                'export_date' => now()->toDateTimeString(),
                'exported_by' => auth()->user()->name,
            ],
            'clients' => $clients->map(function ($client) {
                return [
                    'name' => $client->name,
                    'company' => $client->company,
                    'email' => $client->email,
                    'phone' => $client->phone,
                    'accordo_economico_mensile' => $client->accordo_economico_mensile,
                    'potenziale_upsell' => $client->potenziale_upsell,
                    'livello_soddisfazione' => $client->livello_soddisfazione,
                    'nps_score' => $client->nps_score,
                    'projects_count' => $client->projects_count,
                    'data_acquisizione' => $client->data_acquisizione?->format('Y-m-d'),
                ];
            }),
            'summary' => [
                'total_revenue' => $clients->sum('accordo_economico_mensile'),
                'total_potential_upsell' => $clients->sum('potenziale_upsell'),
                'average_nps' => round($clients->avg('nps_score'), 2),
                'total_projects' => $clients->sum('projects_count'),
            ],
        ];

        $filename = 'tutti_clienti_fusion_media_' . now()->format('Y-m-d') . '.json';

        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
