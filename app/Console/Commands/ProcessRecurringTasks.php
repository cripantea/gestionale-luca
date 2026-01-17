<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class ProcessRecurringTasks extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tasks:process-recurring';

    /**
     * The console command description.
     */
    protected $description = 'Processa le task ricorrenti e crea le prossime istanze';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔄 Processando task ricorrenti...');

        // Trova task ricorrenti completate di recente
        $recurringTasks = Task::where('is_recurring', true)
            ->where('status', 'completato')
            ->whereDate('completed_at', '>=', now()->subDay())
            ->whereDoesntHave('childRecurringTasks', function ($query) {
                $query->where('created_at', '>=', now()->subDay());
            })
            ->get();

        $created = 0;

        foreach ($recurringTasks as $task) {
            try {
                $newTask = $task->createNextRecurrence();
                if ($newTask) {
                    $created++;
                    $this->line("✅ Creata task ricorrente: {$newTask->name} (deadline: {$newTask->deadline->format('Y-m-d')})");
                }
            } catch (\Exception $e) {
                $this->error("❌ Errore creando ricorrenza per task {$task->id}: {$e->getMessage()}");
            }
        }

        // Aggiorna priority score per tutte le task attive
        $this->info('📊 Aggiornando priority scores...');
        $activeTasks = Task::whereIn('status', ['da_fare', 'in_corso', 'in_pausa', 'in_attesa'])->get();
        
        foreach ($activeTasks as $task) {
            $task->updatePriorityScore();
        }

        $this->info("✅ Completato! {$created} task ricorrenti create. {$activeTasks->count()} priority scores aggiornati.");

        return Command::SUCCESS;
    }
}
