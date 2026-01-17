<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Task;
use App\Models\TaskTemplate;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RealClientsSeeder extends Seeder
{
    public function run(): void
    {
        // Create project types if they don't exist
        $typeWeb = ProjectType::firstOrCreate(
            ['name' => 'Sito Web'],
            ['description' => 'Sviluppo e manutenzione siti web']
        );
        
        $typeSocial = ProjectType::firstOrCreate(
            ['name' => 'Gestione Social'],
            ['description' => 'Gestione social media e contenuti']
        );
        
        $typeGestionale = ProjectType::firstOrCreate(
            ['name' => 'Gestionale Custom'],
            ['description' => 'Software gestionali personalizzati']
        );
        
        $typeFusionCore = ProjectType::firstOrCreate(
            ['name' => 'Fusion Core'],
            ['description' => 'Sistema completo Fusion Core']
        );
        
        $typeFusionSite = ProjectType::firstOrCreate(
            ['name' => 'Fusion Site'],
            ['description' => 'Sito web con Fusion Core']
        );
        
        $typeHosting = ProjectType::firstOrCreate(
            ['name' => 'Hosting & Server'],
            ['description' => 'Gestione hosting e server']
        );

        // Get task templates
        $templateReport = TaskTemplate::where('name', 'LIKE', '%Report%')->first();
        $templateManutenzione = TaskTemplate::where('name', 'LIKE', '%Manutenzione%')->first();

        // Delete existing contacts
        Contact::query()->delete();

        // 1. MDL UNIPOL - 2 contratti
        $mdlUnipol = Contact::create([
            'name' => 'MDL Unipol',
            'company' => 'MDL Unipol',
            'status' => 'cliente',
            'accordo_economico_mensile' => 1000.00,
            'tipo_fatturazione' => 'mensile',
            'metodo_pagamento' => 'bonifico',
            'data_prossima_fattura' => Carbon::parse('2026-01-08'),
            'ghl_attivo' => false,
            'whatsapp_attivo' => false,
            'dettagli_accordo' => 'Social 400€/mese + Gestionale Sinistri 600€/anno',
            'potenziale_upsell' => 300.00,
            'note_upsell' => 'WhatsApp marketing 100€, base contatti %, subagente investimenti 200€/mese',
            'settore_business' => 'Assicurazioni',
            'data_acquisizione' => Carbon::parse('2024-01-15'),
            'livello_soddisfazione' => 'soddisfatto',
            'nps_score' => 8,
        ]);

        Project::create(['contact_id' => $mdlUnipol->id, 'project_type_id' => $typeSocial->id, 'name' => 'Gestione Social + Sito MDL', 'status' => 'attivo']);
        Project::create(['contact_id' => $mdlUnipol->id, 'project_type_id' => $typeGestionale->id, 'name' => 'Gestionale Sinistri', 'status' => 'attivo']);

        // 2. VISMARA - 2 contratti
        $vismara = Contact::create([
            'name' => 'Vismara',
            'company' => 'Vismara Srl',
            'status' => 'cliente',
            'accordo_economico_mensile' => 416.67,
            'tipo_fatturazione' => 'trimestrale',
            'metodo_pagamento' => 'bonifico',
            'data_prossima_fattura' => Carbon::parse('2026-04-01'),
            'ghl_attivo' => false,
            'whatsapp_attivo' => false,
            'dettagli_accordo' => 'Gestionale 1250€/trimestre + Server 450€/anno',
            'potenziale_upsell' => 1000.00,
            'note_upsell' => 'Sito web 1000€ o altri gestionali. Possibili referenze B2B',
            'settore_business' => 'Industria/Produzione',
            'data_acquisizione' => Carbon::parse('2023-04-01'),
            'livello_soddisfazione' => 'molto_soddisfatto',
            'nps_score' => 9,
        ]);

        Project::create(['contact_id' => $vismara->id, 'project_type_id' => $typeGestionale->id, 'name' => 'Gestionale Produzione', 'status' => 'attivo']);
        Project::create(['contact_id' => $vismara->id, 'project_type_id' => $typeHosting->id, 'name' => 'Server Dedicato', 'status' => 'attivo']);

        Task::create([
            'project_id' => Project::where('contact_id', $vismara->id)->where('name', 'LIKE', '%Gestionale%')->first()->id,
            'name' => 'Richiedere referenze B2B',
            'description' => 'Chiedere referenze per altre aziende interessate a gestionali custom',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(7),
            'priority' => 'media',
            'priority_score' => 70,
            'estimated_hours' => 1,
            'tags' => json_encode(['referenze', 'business-development']),
            'category' => 'vendita',
        ]);

        // 3. DENTAL EQUIPE
        $dentalEquipe = Contact::create([
            'name' => 'Dental Equipe',
            'company' => 'Dental Equipe',
            'status' => 'cliente',
            'accordo_economico_mensile' => 150.00,
            'tipo_fatturazione' => 'mensile',
            'metodo_pagamento' => 'carta',
            'data_prossima_fattura' => Carbon::parse('2026-02-01'),
            'ghl_attivo' => true,
            'whatsapp_attivo' => true,
            'dettagli_accordo' => 'Fusion Core 150€/mese',
            'potenziale_upsell' => 250.00,
            'note_upsell' => 'WhatsApp marketing 150€ + social 100€. Piano 2026 già inviato - INTERESSATO',
            'settore_business' => 'Odontoiatria',
            'data_acquisizione' => Carbon::parse('2024-02-01'),
            'livello_soddisfazione' => 'molto_soddisfatto',
            'nps_score' => 9,
        ]);

        $projectDental = Project::create(['contact_id' => $dentalEquipe->id, 'project_type_id' => $typeFusionCore->id, 'name' => 'Fusion Core', 'status' => 'attivo']);

        Task::create([
            'project_id' => $projectDental->id,
            'name' => '🔥 Follow-up Piano 2026',
            'description' => 'Contattare per upsell WhatsApp (150€) + Social (100€) - piano già inviato',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(3),
            'priority' => 'alta',
            'priority_score' => 85,
            'estimated_hours' => 2,
            'tags' => json_encode(['upsell', 'urgente', 'hot-lead']),
            'category' => 'vendita',
        ]);

        // 4. LA BOUTIQUE DEL BENESSERE
        $boutique = Contact::create([
            'name' => 'La Boutique del Benessere',
            'company' => 'La Boutique del Benessere',
            'status' => 'cliente',
            'accordo_economico_mensile' => 150.00,
            'tipo_fatturazione' => 'mensile',
            'metodo_pagamento' => 'carta',
            'data_prossima_fattura' => Carbon::parse('2026-01-25'),
            'ghl_attivo' => true,
            'whatsapp_attivo' => true,
            'dettagli_accordo' => 'Fusion Core 150€/mese',
            'potenziale_upsell' => 50.00,
            'note_upsell' => 'Gestionale appuntamenti automatico, ma poco tecniche',
            'settore_business' => 'Wellness & Beauty',
            'data_acquisizione' => Carbon::parse('2024-06-15'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create(['contact_id' => $boutique->id, 'project_type_id' => $typeFusionCore->id, 'name' => 'Fusion Core', 'status' => 'attivo']);

        // 5. BELLEZZAPIÙ
        $bellezzaPiu = Contact::create([
            'name' => 'BellezzaPiù',
            'company' => 'BellezzaPiù',
            'status' => 'cliente',
            'accordo_economico_mensile' => 150.00,
            'tipo_fatturazione' => 'bimensile',
            'metodo_pagamento' => 'contanti',
            'data_prossima_fattura' => Carbon::parse('2026-01-14'),
            'ghl_attivo' => true,
            'whatsapp_attivo' => true,
            'dettagli_accordo' => 'Fusion Core 300€/bimestre',
            'potenziale_upsell' => 0,
            'note_upsell' => 'NO UPSELL - Lead da 6000€, gestisce bene tutto cartaceo',
            'settore_business' => 'Formazione Estetica',
            'data_acquisizione' => Carbon::parse('2024-01-14'),
            'livello_soddisfazione' => 'molto_soddisfatto',
            'nps_score' => 10,
        ]);

        $projectBellezza = Project::create(['contact_id' => $bellezzaPiu->id, 'project_type_id' => $typeFusionCore->id, 'name' => 'Fusion Core', 'status' => 'attivo']);

        Task::create([
            'project_id' => $projectBellezza->id,
            'name' => 'Check-in mensile cliente TOP',
            'description' => 'Verifica soddisfazione cliente NPS 10',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(14),
            'is_recurring' => true,
            'recurrence_pattern' => 'monthly',
            'recurrence_interval' => 1,
            'priority' => 'media',
            'priority_score' => 60,
            'estimated_hours' => 1,
            'tags' => json_encode(['check-in', 'vip-client']),
            'category' => 'supporto',
        ]);

        // 6. FIORELLA RIGAMONTI
        $fiorella = Contact::create([
            'name' => 'Fiorella Rigamonti',
            'company' => 'Fiorella Rigamonti',
            'status' => 'cliente',
            'accordo_economico_mensile' => 100.00,
            'tipo_fatturazione' => 'mensile',
            'metodo_pagamento' => 'bonifico',
            'data_prossima_fattura' => Carbon::parse('2026-01-30'),
            'ghl_attivo' => false,
            'whatsapp_attivo' => false,
            'dettagli_accordo' => 'Sito web 100€/mese',
            'potenziale_upsell' => 200.00,
            'note_upsell' => 'WhatsApp marketing 200€ o % su base contatti',
            'data_acquisizione' => Carbon::parse('2024-08-20'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create(['contact_id' => $fiorella->id, 'project_type_id' => $typeWeb->id, 'name' => 'Sito Web', 'status' => 'attivo']);

        // 7. FAIRY TOOTH - 2 contratti
        $fairyTooth = Contact::create([
            'name' => 'Fairy Tooth',
            'company' => 'Fairy Tooth - Studio Dentistico',
            'status' => 'cliente',
            'accordo_economico_mensile' => 100.00,
            'tipo_fatturazione' => 'bimensile',
            'metodo_pagamento' => 'contanti',
            'data_prossima_fattura' => Carbon::parse('2026-02-04'),
            'ghl_attivo' => false,
            'whatsapp_attivo' => false,
            'dettagli_accordo' => 'Sito + Report + Blog 200€/bimestre + 20% corsi oltre 2/mese',
            'potenziale_upsell' => 0,
            'note_upsell' => 'Basta Fusion - focus su referenze',
            'settore_business' => 'Odontoiatria',
            'data_acquisizione' => Carbon::parse('2024-03-10'),
            'livello_soddisfazione' => 'molto_soddisfatto',
        ]);

        Project::create(['contact_id' => $fairyTooth->id, 'project_type_id' => $typeWeb->id, 'name' => 'Sito + Report + Blog', 'status' => 'attivo']);

        // 8. DEA BAGS
        $deaBags = Contact::create([
            'name' => 'Dea Bags',
            'company' => 'Dea Bags',
            'status' => 'cliente',
            'accordo_economico_mensile' => 100.00,
            'tipo_fatturazione' => 'mensile',
            'metodo_pagamento' => 'bonifico',
            'data_prossima_fattura' => Carbon::now()->addDays(15),
            'ghl_attivo' => false,
            'whatsapp_attivo' => false,
            'dettagli_accordo' => 'Assistenza e-commerce 100€/mese',
            'potenziale_upsell' => 500.00,
            'note_upsell' => 'ManyChat chatbot AI + Meta Ads 300-500€/mese. Chiedere referenze e-commerce',
            'settore_business' => 'E-commerce Moda',
            'data_acquisizione' => Carbon::parse('2024-05-20'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create(['contact_id' => $deaBags->id, 'project_type_id' => $typeWeb->id, 'name' => 'E-commerce', 'status' => 'attivo']);

        // 9. ARIEL
        $ariel = Contact::create([
            'name' => 'Ariel',
            'company' => 'Ariel Beauty',
            'status' => 'cliente',
            'accordo_economico_mensile' => 47.00,
            'tipo_fatturazione' => 'mensile',
            'metodo_pagamento' => 'carta',
            'data_prossima_fattura' => Carbon::parse('2026-02-02'),
            'ghl_attivo' => false,
            'whatsapp_attivo' => false,
            'dettagli_accordo' => 'Fusion Site 47€/mese',
            'potenziale_upsell' => 50.00,
            'note_upsell' => 'Gestionale estetico 30-50€/mese - STRATEGICO per entrare nel settore gestionali estetici',
            'settore_business' => 'Estetica',
            'data_acquisizione' => Carbon::parse('2024-10-02'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create(['contact_id' => $ariel->id, 'project_type_id' => $typeFusionSite->id, 'name' => 'Fusion Site', 'status' => 'attivo']);

        // 10. MAD FOR HAIR
        $madForHair = Contact::create([
            'name' => 'Mad for Hair',
            'company' => 'Mad for Hair',
            'status' => 'cliente',
            'accordo_economico_mensile' => 39.17,
            'tipo_fatturazione' => 'annuale',
            'metodo_pagamento' => 'carta',
            'data_prossima_fattura' => Carbon::parse('2026-12-19'),
            'ghl_attivo' => true,
            'whatsapp_attivo' => true,
            'dettagli_accordo' => 'Fusion Site 470€/anno',
            'potenziale_upsell' => 500.00,
            'note_upsell' => '🔥 ALTA PRIORITÀ: Gestione social 500€+/mese - cliente MOLTO interessato',
            'settore_business' => 'Parrucchieri',
            'data_acquisizione' => Carbon::parse('2023-12-19'),
            'livello_soddisfazione' => 'molto_soddisfatto',
            'nps_score' => 9,
        ]);

        $projectMad = Project::create(['contact_id' => $madForHair->id, 'project_type_id' => $typeFusionSite->id, 'name' => 'Fusion Site', 'status' => 'attivo']);

        Task::create([
            'project_id' => $projectMad->id,
            'name' => '💎 Proposta Social 500€/mese',
            'description' => 'Preparare proposta dettagliata gestione social completa - cliente MOLTO interessato',
            'status' => 'in_corso',
            'started_at' => Carbon::now(),
            'deadline' => Carbon::now()->addDays(5),
            'priority' => 'critica',
            'priority_score' => 95,
            'estimated_hours' => 4,
            'tags' => json_encode(['upsell', 'alto-valore', 'urgente', 'hot-lead']),
            'category' => 'vendita',
        ]);

        // 11. STUDIO 3
        $studio3 = Contact::create([
            'name' => 'Studio 3',
            'company' => 'Studio 3',
            'status' => 'cliente',
            'accordo_economico_mensile' => 39.17,
            'tipo_fatturazione' => 'annuale',
            'metodo_pagamento' => 'carta',
            'data_prossima_fattura' => Carbon::parse('2026-12-05'),
            'ghl_attivo' => false,
            'whatsapp_attivo' => false,
            'dettagli_accordo' => 'Fusion Site 470€/anno',
            'potenziale_upsell' => 100.00,
            'note_upsell' => 'Gestione base social 100€/mese. Possibili referenze',
            'data_acquisizione' => Carbon::parse('2023-12-05'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create(['contact_id' => $studio3->id, 'project_type_id' => $typeFusionSite->id, 'name' => 'Fusion Site', 'status' => 'attivo']);

        // 12. I TRE GIOIELLI
        $treGioielli = Contact::create([
            'name' => 'I Tre Gioielli',
            'company' => 'I Tre Gioielli',
            'status' => 'cliente',
            'accordo_economico_mensile' => 27.00,
            'tipo_fatturazione' => 'trimestrale',
            'metodo_pagamento' => 'bonifico',
            'data_prossima_fattura' => Carbon::parse('2026-03-01'),
            'ghl_attivo' => false,
            'whatsapp_attivo' => false,
            'dettagli_accordo' => 'Hosting + sito 81€/trimestre',
            'potenziale_upsell' => 0,
            'note_upsell' => '⚠️ DA TAGLIARE - Cliente problematica rompiballe',
            'settore_business' => 'Gioielleria',
            'data_acquisizione' => Carbon::parse('2023-12-01'),
            'livello_soddisfazione' => 'insoddisfatto',
            'nps_score' => 3,
        ]);

        $projectGioielli = Project::create(['contact_id' => $treGioielli->id, 'project_type_id' => $typeHosting->id, 'name' => 'Hosting + Sito', 'status' => 'attivo']);

        Task::create([
            'project_id' => $projectGioielli->id,
            'name' => '⚠️ Valutare non rinnovo marzo',
            'description' => 'Decidere se rinnovare contratto cliente problematica - scadenza marzo',
            'status' => 'da_fare',
            'deadline' => Carbon::parse('2026-02-15'),
            'priority' => 'bassa',
            'priority_score' => 40,
            'estimated_hours' => 1,
            'tags' => json_encode(['admin', 'cliente-problematico']),
            'category' => 'admin',
        ]);

        // 13. STUDIO ALBANI 51
        $albani = Contact::create([
            'name' => 'Studio Albani 51',
            'company' => 'Studio Albani 51',
            'status' => 'cliente',
            'accordo_economico_mensile' => 0,
            'tipo_fatturazione' => 'commissionale',
            'metodo_pagamento' => 'bonifico',
            'data_prossima_fattura' => null,
            'ghl_attivo' => false,
            'whatsapp_attivo' => true,
            'dettagli_accordo' => '15% su deal chiusi - pagamento dopo incasso',
            'potenziale_upsell' => 50.00,
            'note_upsell' => 'Sito quando scade 30-50€/mese e/o Fusion Core',
            'settore_business' => 'Immobiliare',
            'data_acquisizione' => Carbon::parse('2024-09-01'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create(['contact_id' => $albani->id, 'project_type_id' => $typeSocial->id, 'name' => 'Lead Generation Immobiliare', 'status' => 'attivo']);
    }
}
