<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Spesa;
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

        // Calcoli finanziari - Logica corretta
        // 1. MRR Lordo (somma tutti i clienti)
        $mrrLordo = Contact::sum('accordo_economico_mensile');
        
        // 2. MRR Netto (togli 25% contributi subito)
        $mrrNetto = $mrrLordo * 0.75;
        
        // 3. Spese ricorrenti (tutte le spese attive non una tantum)
        $speseMensili = Spesa::attive()->ricorrenti()->sum('importo_mensile');
        
        // 4. Utile (MRR Netto - Spese)
        $utile = $mrrNetto - $speseMensili;
        
        // 5. Potenziale Upsell (somma da tutti i clienti)
        $potenzialeUpsell = Contact::sum('potenziale_upsell');
        
        // Statistiche generali
        $stats = [
            'total_clients' => Contact::count(),
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'attivo')->count(),
            'tasks_completed_this_month' => Task::where('status', 'completato')
                ->whereMonth('completed_at', now()->month)
                ->whereYear('completed_at', now()->year)
                ->count(),
            // I 4 dati principali
            'mrr_lordo' => $mrrLordo,
            'mrr_netto' => $mrrNetto,
            'spese_mensili' => $speseMensili,
            'utile' => $utile,
            'potenziale_upsell' => $potenzialeUpsell,
        ];

        // Prossime fatture (prossimi 30 giorni)
        $upcomingInvoices = Contact::whereNotNull('data_prossima_fattura')
            ->where('data_prossima_fattura', '>=', now())
            ->where('data_prossima_fattura', '<=', now()->addDays(30))
            ->orderBy('data_prossima_fattura')
            ->get(['id', 'name', 'company', 'accordo_economico_mensile', 'tipo_fatturazione', 'data_prossima_fattura'])
            ->map(function ($client) {
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'company' => $client->company,
                    'amount' => $client->accordo_economico_mensile,
                    'tipo' => $client->tipo_fatturazione,
                    'data' => $client->data_prossima_fattura,
                    'days_until' => now()->diffInDays($client->data_prossima_fattura, false),
                ];
            });

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

        // Spese straordinarie in arrivo (prossimi 6 mesi)
        $speseStratordinarie = Spesa::unaTantum()
            ->whereNotNull('data_scadenza')
            ->where('data_scadenza', '>=', now())
            ->where('data_scadenza', '<=', now()->addMonths(6))
            ->orderBy('data_scadenza')
            ->get()
            ->map(function ($spesa) {
                return [
                    'id' => $spesa->id,
                    'nome' => $spesa->nome,
                    'categoria' => $spesa->categoria,
                    'importo' => $spesa->importo_totale,
                    'data_scadenza' => $spesa->data_scadenza,
                    'days_until' => now()->diffInDays($spesa->data_scadenza, false),
                ];
            });

        return Inertia::render('Dashboard', [
            'tasks' => $tasks,
            'urgentTasks' => $urgentTasks->values(),
            'stats' => $stats,
            'activeProjects' => $activeProjects,
            'upcomingInvoices' => $upcomingInvoices,
            'speseStratordinarie' => $speseStratordinarie,
        ]);
    }
}

