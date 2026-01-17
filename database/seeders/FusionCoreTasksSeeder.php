<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Project;
use App\Models\Contact;
use App\Models\ProjectType;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FusionCoreTasksSeeder extends Seeder
{
    public function run(): void
    {
        $fusionCoreType = ProjectType::where('name', 'Fusion Core')->first();
        
        // Clienti Fusion Core
        $dentalEquipe = Contact::where('name', 'Dental Equipe')->first();
        $boutique = Contact::where('name', 'La Boutique del Benessere')->first();
        $bellezzaPiu = Contact::where('name', 'BellezzaPiù')->first();
        
        $projectDental = Project::where('contact_id', $dentalEquipe->id)->where('project_type_id', $fusionCoreType->id)->first();
        $projectBoutique = Project::where('contact_id', $boutique->id)->where('project_type_id', $fusionCoreType->id)->first();
        $projectBellezza = Project::where('contact_id', $bellezzaPiu->id)->where('project_type_id', $fusionCoreType->id)->first();

        // ==================== TASK STRATEGICHE FUSION CORE ====================

        // 1. TEMPLATE & SNAPSHOT
        Task::create([
            'project_id' => $projectDental->id,
            'name' => '🔧 Aggiornamento Snapshot Master Fusion Core',
            'description' => 'Aggiornare snapshot master con ultimi miglioramenti: ottimizzazione workflow, nuovi template WhatsApp, miglioramenti form. IMPORTANTE: testare prima in sub-account test.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(10),
            'priority' => 'media',
            'priority_score' => 70,
            'estimated_hours' => 4,
            'tags' => json_encode(['fusion-core', 'template', 'snapshot', 'tecnico']),
            'category' => 'sviluppo',
        ]);

        // 2. CAMPAGNA RECENSIONI - Dental Equipe
        Task::create([
            'project_id' => $projectDental->id,
            'name' => '⭐ Avviare Campagna Recensioni Mensile',
            'description' => 'Attivare workflow recensioni per Dental Equipe. Import ultimi 20 pazienti soddisfatti. Obiettivo: +5 recensioni Google questo mese.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(5),
            'is_recurring' => true,
            'recurrence_pattern' => 'monthly',
            'recurrence_interval' => 1,
            'priority' => 'alta',
            'priority_score' => 80,
            'estimated_hours' => 2,
            'tags' => json_encode(['fusion-core', 'recensioni', 'marketing', 'ricorrente']),
            'category' => 'marketing',
        ]);

        // 3. CAMPAGNA RECENSIONI - BellezzaPiù
        Task::create([
            'project_id' => $projectBellezza->id,
            'name' => '⭐ Campagna Recensioni BellezzaPiù',
            'description' => 'Cliente NPS 10 - ottimo per recensioni. Import lead formazione recenti (cartaceo). Obiettivo: +10 recensioni (lead alto valore).',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(7),
            'is_recurring' => true,
            'recurrence_pattern' => 'monthly',
            'recurrence_interval' => 1,
            'priority' => 'alta',
            'priority_score' => 85,
            'estimated_hours' => 2,
            'tags' => json_encode(['fusion-core', 'recensioni', 'vip-client', 'ricorrente']),
            'category' => 'marketing',
        ]);

        // 4. ONBOARDING - Ariel (potenziale)
        $ariel = Contact::where('name', 'Ariel')->first();
        $projectAriel = Project::where('contact_id', $ariel->id)->first();
        
        Task::create([
            'project_id' => $projectAriel->id,
            'name' => '🎯 Proposta Upgrade a Fusion Core',
            'description' => 'Ariel ha Fusion Site (47€). Proporre upgrade a Fusion Core (150€) con gestionale appuntamenti integrato. Cliente ha già detto che le sarebbe UTILE.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(14),
            'priority' => 'media',
            'priority_score' => 75,
            'estimated_hours' => 3,
            'tags' => json_encode(['fusion-core', 'upsell', 'proposta', 'strategico']),
            'category' => 'vendita',
        ]);

        // 5. TESTING MENSILE
        Task::create([
            'project_id' => $projectDental->id,
            'name' => '✅ Test Completo Flussi Fusion Core',
            'description' => 'Test mensile: 1) Form contatto, 2) Chat widget, 3) Workflow notifiche, 4) Template WhatsApp, 5) Auto-reply. Documentare eventuali bug.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(20),
            'is_recurring' => true,
            'recurrence_pattern' => 'monthly',
            'recurrence_interval' => 1,
            'priority' => 'media',
            'priority_score' => 65,
            'estimated_hours' => 2,
            'tags' => json_encode(['fusion-core', 'testing', 'qa', 'ricorrente']),
            'category' => 'supporto',
        ]);

        // 6. ONBOARDING - Fiorella Rigamonti
        $fiorella = Contact::where('name', 'Fiorella Rigamonti')->first();
        $projectFiorella = Project::where('contact_id', $fiorella->id)->first();
        
        Task::create([
            'project_id' => $projectFiorella->id,
            'name' => '💎 Proposta Fusion Core + WhatsApp Marketing',
            'description' => 'Fiorella ha sito base (100€). Proporre Fusion Core (150€) + WhatsApp marketing (200€) = 350€ totale. Upsell +250€/mese.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(21),
            'priority' => 'media',
            'priority_score' => 70,
            'estimated_hours' => 3,
            'tags' => json_encode(['fusion-core', 'upsell', 'whatsapp', 'proposta']),
            'category' => 'vendita',
        ]);

        // 7. CUSTOM VALUES - Check Boutique
        Task::create([
            'project_id' => $projectBoutique->id,
            'name' => '🔍 Verifica Custom Values Aggiornati',
            'description' => 'Verificare che tutti i Custom Values siano corretti: nome attività, città, numero WhatsApp, email, link Google Reviews. Cliente poco tech-savvy.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(7),
            'priority' => 'media',
            'priority_score' => 60,
            'estimated_hours' => 1,
            'tags' => json_encode(['fusion-core', 'manutenzione', 'custom-values']),
            'category' => 'supporto',
        ]);

        // 8. MATERIALE MARKETING
        Task::create([
            'project_id' => $projectDental->id,
            'name' => '📄 Creare Case Study Fusion Core',
            'description' => 'Creare case study con risultati Dental Equipe (NPS 9, GHL attivo, WhatsApp attivo). Da usare per proporre Fusion Core ad altri dentisti.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(15),
            'priority' => 'bassa',
            'priority_score' => 55,
            'estimated_hours' => 3,
            'tags' => json_encode(['fusion-core', 'marketing', 'case-study', 'content']),
            'category' => 'marketing',
        ]);

        // 9. WORKFLOW OTTIMIZZAZIONE
        Task::create([
            'project_id' => $projectDental->id,
            'name' => '⚡ Ottimizzare Workflow Auto-Reply',
            'description' => 'Analizzare performance workflow 4 (auto-reply). Verificare tempi risposta, tasso apertura template WhatsApp. Ottimizzare messaggi se necessario.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(25),
            'priority' => 'bassa',
            'priority_score' => 50,
            'estimated_hours' => 2,
            'tags' => json_encode(['fusion-core', 'workflow', 'ottimizzazione', 'whatsapp']),
            'category' => 'sviluppo',
        ]);

        // 10. PROPOSTA NUOVI CLIENTI - Dental sector
        Task::create([
            'project_id' => $projectDental->id,
            'name' => '🦷 Proposta Fusion Core ad Altri Dentisti',
            'description' => 'Chiedere referenze a Dental Equipe (NPS 9) per altri studi dentistici. Settore odontoiatrico ideale per Fusion Core. Preparare proposta dedicata.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(30),
            'priority' => 'media',
            'priority_score' => 75,
            'estimated_hours' => 4,
            'tags' => json_encode(['fusion-core', 'referenze', 'dentisti', 'vendita']),
            'category' => 'vendita',
        ]);

        // 11. DOCUMENTAZIONE
        Task::create([
            'project_id' => $projectDental->id,
            'name' => '📚 Aggiornare Documentazione Onboarding',
            'description' => 'Aggiornare documento operativo Fusion Core con checklist onboarding, troubleshooting comuni, FAQ clienti. Versione PDF da inviare a nuovi clienti.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(20),
            'priority' => 'bassa',
            'priority_score' => 45,
            'estimated_hours' => 3,
            'tags' => json_encode(['fusion-core', 'documentazione', 'onboarding']),
            'category' => 'admin',
        ]);

        // 12. ANALISI PERFORMANCE
        Task::create([
            'project_id' => $projectBellezza->id,
            'name' => '📊 Report Mensile Performance Fusion Core',
            'description' => 'Analizzare metriche tutti clienti Fusion Core: lead generati, tasso conversione form, recensioni raccolte, messaggi WhatsApp. Dashboard comparativa.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(28),
            'is_recurring' => true,
            'recurrence_pattern' => 'monthly',
            'recurrence_interval' => 1,
            'priority' => 'media',
            'priority_score' => 65,
            'estimated_hours' => 2,
            'tags' => json_encode(['fusion-core', 'analytics', 'report', 'ricorrente']),
            'category' => 'admin',
        ]);

        // 13. TEMPLATE WHATSAPP - Nuovi
        Task::create([
            'project_id' => $projectDental->id,
            'name' => '📱 Creare Template WhatsApp per Follow-up',
            'description' => 'Sviluppare 2 nuovi template: 1) Follow-up richiesta dopo 48h (se nessuna risposta), 2) Ringraziamento post-servizio. Testare e aggiungere a snapshot.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(18),
            'priority' => 'media',
            'priority_score' => 70,
            'estimated_hours' => 3,
            'tags' => json_encode(['fusion-core', 'whatsapp', 'template', 'sviluppo']),
            'category' => 'sviluppo',
        ]);

        // 14. UPSELL INTERNO - La Boutique
        Task::create([
            'project_id' => $projectBoutique->id,
            'name' => '💡 Proposta Gestionale Appuntamenti',
            'description' => 'La Boutique ha Fusion Core. Proporre add-on gestionale appuntamenti automatico (30-50€/mese). Cliente poco tech ma potenziale interesse.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(30),
            'priority' => 'bassa',
            'priority_score' => 50,
            'estimated_hours' => 2,
            'tags' => json_encode(['fusion-core', 'upsell', 'gestionale', 'add-on']),
            'category' => 'vendita',
        ]);

        // 15. BACKUP & SICUREZZA
        Task::create([
            'project_id' => $projectDental->id,
            'name' => '💾 Backup Snapshot Fusion Core',
            'description' => 'Creare backup completo snapshot master Fusion Core. Documentare tutte le configurazioni, workflow, template. Salvare in repository sicuro.',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(7),
            'priority' => 'alta',
            'priority_score' => 80,
            'estimated_hours' => 2,
            'tags' => json_encode(['fusion-core', 'backup', 'sicurezza', 'critico']),
            'category' => 'admin',
        ]);
    }
}
