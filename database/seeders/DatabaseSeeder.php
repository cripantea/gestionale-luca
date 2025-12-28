<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Contact;
use App\Models\ProjectType;
use App\Models\TaskTemplate;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gestionale.test',
            'password' => bcrypt('password'),
        ]);

        // Create Project Types
        $sviluppoSito = ProjectType::create([
            'name' => 'Sviluppo Sito Web',
            'description' => 'Sviluppo di siti web completi',
        ]);

        $marketingWhatsapp = ProjectType::create([
            'name' => 'Marketing WhatsApp',
            'description' => 'Campagne marketing tramite WhatsApp',
        ]);

        $seo = ProjectType::create([
            'name' => 'SEO',
            'description' => 'Ottimizzazione per motori di ricerca',
        ]);

        // Create Task Templates for Sviluppo Sito
        TaskTemplate::create([
            'project_type_id' => $sviluppoSito->id,
            'name' => 'Analisi Requisiti',
            'description' => 'Raccolta e analisi dei requisiti del cliente',
            'default_deadline_days' => 2,
            'order' => 1,
        ]);

        TaskTemplate::create([
            'project_type_id' => $sviluppoSito->id,
            'name' => 'Design Mockup',
            'description' => 'Creazione mockup grafici',
            'default_deadline_days' => 5,
            'order' => 2,
        ]);

        TaskTemplate::create([
            'project_type_id' => $sviluppoSito->id,
            'name' => 'Sviluppo Frontend',
            'description' => 'Implementazione interfaccia utente',
            'default_deadline_days' => 10,
            'order' => 3,
        ]);

        TaskTemplate::create([
            'project_type_id' => $sviluppoSito->id,
            'name' => 'Sviluppo Backend',
            'description' => 'Implementazione logica server',
            'default_deadline_days' => 10,
            'order' => 4,
        ]);

        TaskTemplate::create([
            'project_type_id' => $sviluppoSito->id,
            'name' => 'Testing',
            'description' => 'Test e correzione bug',
            'default_deadline_days' => 3,
            'order' => 5,
        ]);

        TaskTemplate::create([
            'project_type_id' => $sviluppoSito->id,
            'name' => 'Deploy',
            'description' => 'Pubblicazione sito online',
            'default_deadline_days' => 1,
            'order' => 6,
        ]);

        // Create Task Templates for Marketing WhatsApp
        TaskTemplate::create([
            'project_type_id' => $marketingWhatsapp->id,
            'name' => 'Strategia Campagna',
            'description' => 'Definizione strategia e target',
            'default_deadline_days' => 2,
            'order' => 1,
        ]);

        TaskTemplate::create([
            'project_type_id' => $marketingWhatsapp->id,
            'name' => 'Creazione Contenuti',
            'description' => 'Preparazione messaggi e materiali',
            'default_deadline_days' => 3,
            'order' => 2,
        ]);

        TaskTemplate::create([
            'project_type_id' => $marketingWhatsapp->id,
            'name' => 'Configurazione Sistema',
            'description' => 'Setup sistema invio messaggi',
            'default_deadline_days' => 1,
            'order' => 3,
        ]);

        TaskTemplate::create([
            'project_type_id' => $marketingWhatsapp->id,
            'name' => 'Lancio Campagna',
            'description' => 'Avvio invio messaggi',
            'default_deadline_days' => 1,
            'order' => 4,
        ]);

        TaskTemplate::create([
            'project_type_id' => $marketingWhatsapp->id,
            'name' => 'Monitoraggio Risultati',
            'description' => 'Analisi performance campagna',
            'default_deadline_days' => 7,
            'order' => 5,
        ]);

        // Create Task Templates for SEO
        TaskTemplate::create([
            'project_type_id' => $seo->id,
            'name' => 'Audit SEO',
            'description' => 'Analisi stato attuale SEO',
            'default_deadline_days' => 3,
            'order' => 1,
        ]);

        TaskTemplate::create([
            'project_type_id' => $seo->id,
            'name' => 'Ricerca Keywords',
            'description' => 'Identificazione parole chiave target',
            'default_deadline_days' => 2,
            'order' => 2,
        ]);

        TaskTemplate::create([
            'project_type_id' => $seo->id,
            'name' => 'Ottimizzazione On-Page',
            'description' => 'Miglioramenti SEO on-page',
            'default_deadline_days' => 5,
            'order' => 3,
        ]);

        TaskTemplate::create([
            'project_type_id' => $seo->id,
            'name' => 'Link Building',
            'description' => 'Acquisizione backlink',
            'default_deadline_days' => 10,
            'order' => 4,
        ]);

        // Create Contacts
        $contatto1 = Contact::create([
            'name' => 'Mario Rossi',
            'email' => 'mario.rossi@example.com',
            'phone' => '+39 333 1234567',
            'company' => 'Rossi SRL',
            'status' => 'contatto',
        ]);

        $cliente1 = Contact::create([
            'name' => 'Giulia Bianchi',
            'email' => 'giulia.bianchi@example.com',
            'phone' => '+39 333 7654321',
            'company' => 'Bianchi SpA',
            'status' => 'cliente',
        ]);

        $cliente2 = Contact::create([
            'name' => 'Luca Verdi',
            'email' => 'luca.verdi@example.com',
            'phone' => '+39 333 9876543',
            'company' => 'Verdi Group',
            'status' => 'cliente',
        ]);

        // Create Projects
        $progetto1 = Project::create([
            'contact_id' => $cliente1->id,
            'project_type_id' => $sviluppoSito->id,
            'name' => 'E-commerce Bianchi',
            'description' => 'Sviluppo piattaforma e-commerce completa',
            'status' => 'attivo',
        ]);

        $progetto2 = Project::create([
            'contact_id' => $cliente2->id,
            'project_type_id' => $marketingWhatsapp->id,
            'name' => 'Campagna Primavera 2025',
            'description' => 'Campagna marketing WhatsApp per promozione nuovi prodotti',
            'status' => 'attivo',
        ]);

        // Generate tasks for projects
        $progetto1->generateTasksFromTemplates();
        $progetto2->generateTasksFromTemplates();

        // Modify some task deadlines to have overdue and due today
        $tasks = \App\Models\Task::all();
        if ($tasks->count() > 0) {
            // Make first task overdue
            $tasks[0]->update(['deadline' => now()->subDays(2)]);

            // Make second task due today
            if ($tasks->count() > 1) {
                $tasks[1]->update(['deadline' => now()->addHours(6)]);
            }

            // Make third task in progress
            if ($tasks->count() > 2) {
                $tasks[2]->start();
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Login with: admin@gestionale.test / password');
    }
}
