<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Contact;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Task;
use App\Models\TaskTemplate;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gestionale.test',
        ]);

        // ==================== PROJECT TYPES ====================
        $typeWeb = ProjectType::create([
            'name' => 'Sito Web',
            'description' => 'Sviluppo e manutenzione siti web',
        ]);

        $typeSocial = ProjectType::create([
            'name' => 'Gestione Social',
            'description' => 'Gestione social media e contenuti',
        ]);

        $typeGestionale = ProjectType::create([
            'name' => 'Gestionale Custom',
            'description' => 'Sviluppo software gestionale su misura',
        ]);

        $typeFusionCore = ProjectType::create([
            'name' => 'Fusion Core',
            'description' => 'Pacchetto Fusion Core completo',
        ]);

        $typeFusionSite = ProjectType::create([
            'name' => 'Fusion Site',
            'description' => 'Pacchetto Fusion Site',
        ]);

        $typeHosting = ProjectType::create([
            'name' => 'Hosting & Server',
            'description' => 'Gestione hosting e server',
        ]);

        // ==================== TASK TEMPLATES ====================
        $templateSocial = TaskTemplate::create([
            'project_type_id' => $typeSocial->id,
            'name' => 'Post Social Mensili',
            'description' => 'Creazione e pubblicazione post social',
            'default_deadline_days' => 7,
            'order' => 1,
        ]);

        $templateReport = TaskTemplate::create([
            'project_type_id' => $typeFusionCore->id,
            'name' => 'Report Mensile',
            'description' => 'Analisi performance e report cliente',
            'default_deadline_days' => 5,
            'order' => 2,
        ]);

        $templateManutenzione = TaskTemplate::create([
            'project_type_id' => $typeWeb->id,
            'name' => 'Manutenzione Sito',
            'description' => 'Aggiornamenti e manutenzione ordinaria',
            'default_deadline_days' => 3,
            'order' => 1,
        ]);

        $templateBlog = TaskTemplate::create([
            'project_type_id' => $typeWeb->id,
            'name' => 'Articolo Blog',
            'description' => 'Scrittura e pubblicazione articolo blog',
            'default_deadline_days' => 14,
            'order' => 3,
        ]);

        // ==================== CLIENTI ====================
        // I clienti reali vengono caricati dal ClientiRealiSeeder
        // Usa: php artisan db:seed --class=ClientiRealiSeeder
        
        // Oppure decommentare qui sotto per test:
        /*
        // 1. MDL UNIPOL
        $mdlUnipol = Contact::create([
            'name' => 'MDL Unipol',
            'email' => 'info@mdlunipol.it',
            'phone' => '+39 333 1234567',
            'company' => 'MDL Unipol',
            'status' => 'cliente',
            'accordo_economico_mensile' => 1000.00, // 400 + 600 (due contratti)
            'tipo_fatturazione' => 'mensile', // Social mensile, gestionale annuale
            'metodo_pagamento' => 'bonifico',
            'data_prossima_fattura' => Carbon::parse('2026-01-08'),
            'ghl_attivo' => false,
            'whatsapp_attivo' => false,
            'dettagli_accordo' => 'Gestione social e sito (400€/mese) + Gestionale Sinistri (600€/anno)',
            'potenziale_upsell' => 300.00,
            'note_upsell' => 'WhatsApp marketing 100€, sfruttare base contatti per %, diventare subagente responsabile investimenti per curriculum futuro 2 giorni/settimana 200€/mese. Software gestionale migliorato possibile.',
            'esigenze_specifiche' => 'Due contratti separati: social mensile e gestionale annuale (prossima fattura 1 luglio).',
            'settore_business' => 'Assicurazioni',
            'canale_preferito' => 'email',
            'data_acquisizione' => Carbon::parse('2024-01-15'),
            'livello_soddisfazione' => 'soddisfatto',
            'nps_score' => 8,
        ]);

        Project::create([
            'contact_id' => $mdlUnipol->id,
            'project_type_id' => $typeSocial->id,
            'name' => 'Gestione Social e Sito MDL',
            'description' => 'Gestione social media e manutenzione sito web - Mensile 400€',
            'status' => 'attivo',
        ]);

        Project::create([
            'contact_id' => $mdlUnipol->id,
            'project_type_id' => $typeGestionale->id,
            'name' => 'Gestionale Sinistri',
            'description' => 'Software gestionale per gestione sinistri - Annuale 600€',
            'status' => 'attivo',
        ]);

        // 2. FIORELLA RIGAMONTI
        $fiorella = Contact::create([
            'name' => 'Fiorella Rigamonti',
            'email' => 'fiorella.rigamonti@example.it',
            'phone' => '+39 340 9876543',
            'company' => 'Fiorella Rigamonti',
            'status' => 'cliente',
            'accordo_economico_mensile' => 100.00,
            'dettagli_accordo' => 'Sito web - Mensile 100€ bonifico',
            'potenziale_upsell' => 200.00,
            'note_upsell' => 'WhatsApp marketing 200€ o sfruttare base contatti per %',
            'esigenze_specifiche' => 'Prossima fattura: 30 gennaio',
            'canale_preferito' => 'email',
            'data_acquisizione' => Carbon::parse('2024-08-20'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create([
            'contact_id' => $fiorella->id,
            'project_type_id' => $typeWeb->id,
            'name' => 'Sito Web Fiorella',
            'description' => 'Manutenzione sito web mensile',
            'status' => 'attivo',
        ]);

        // 3. FAIRY TOOTH
        $fairyTooth = Contact::create([
            'name' => 'Fairy Tooth',
            'email' => 'info@fairytooth.it',
            'phone' => '+39 345 1112233',
            'company' => 'Fairy Tooth - Studio Dentistico',
            'status' => 'cliente',
            'accordo_economico_mensile' => 100.00, // 200 bimensile = 100/mese
            'dettagli_accordo' => 'Sito web + report + blog mensile (200€ bimensile) + 20% su corsi venduti oltre 2/mese',
            'potenziale_upsell' => 0,
            'note_upsell' => 'Basta Fusion - no upsell immediato, focus su referenze',
            'esigenze_specifiche' => 'Fatturazione bimensile cash, prossima: 4 febbraio. GHL: Basta Fusion.',
            'settore_business' => 'Odontoiatria',
            'canale_preferito' => 'email',
            'data_acquisizione' => Carbon::parse('2024-03-10'),
            'livello_soddisfazione' => 'molto_soddisfatto',
        ]);

        Project::create([
            'contact_id' => $fairyTooth->id,
            'project_type_id' => $typeWeb->id,
            'name' => 'Sito Web + Report + Blog',
            'description' => 'Gestione sito, report mensile e articoli blog',
            'status' => 'attivo',
        ]);

        // 4. DENTAL EQUIPE
        $dentalEquipe = Contact::create([
            'name' => 'Dental Equipe',
            'email' => 'info@dentalequipe.it',
            'phone' => '+39 338 4445566',
            'company' => 'Dental Equipe',
            'status' => 'cliente',
            'accordo_economico_mensile' => 150.00,
            'dettagli_accordo' => 'Fusion Core - Mensile 150€ carta/bonifico',
            'potenziale_upsell' => 250.00,
            'note_upsell' => 'WhatsApp marketing a 150€ e social a 100€. Piano 2026 già inviato - cliente interessato.',
            'esigenze_specifiche' => 'Prossima fattura: 1 febbraio. GHL attivo. WhatsApp attivo.',
            'settore_business' => 'Odontoiatria',
            'canale_preferito' => 'whatsapp',
            'data_acquisizione' => Carbon::parse('2024-02-01'),
            'livello_soddisfazione' => 'molto_soddisfatto',
            'nps_score' => 9,
        ]);

        Project::create([
            'contact_id' => $dentalEquipe->id,
            'project_type_id' => $typeFusionCore->id,
            'name' => 'Fusion Core Dental Equipe',
            'description' => 'Pacchetto Fusion Core completo',
            'status' => 'attivo',
        ]);

        // 5. LA BOUTIQUE DEL BENESSERE
        $boutique = Contact::create([
            'name' => 'La Boutique del Benessere',
            'email' => 'info@boutiquedelbenessere.it',
            'phone' => '+39 333 7778889',
            'company' => 'La Boutique del Benessere',
            'status' => 'cliente',
            'accordo_economico_mensile' => 150.00,
            'dettagli_accordo' => 'Fusion Core - Mensile 150€ carta',
            'potenziale_upsell' => 50.00,
            'note_upsell' => 'Potenziale upsell a gestione appuntamenti automatica (gestionale), ma poco tecniche.',
            'esigenze_specifiche' => 'Prossima fattura: 25 gennaio. GHL attivo. WhatsApp attivo.',
            'settore_business' => 'Wellness & Beauty',
            'canale_preferito' => 'whatsapp',
            'data_acquisizione' => Carbon::parse('2024-06-15'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create([
            'contact_id' => $boutique->id,
            'project_type_id' => $typeFusionCore->id,
            'name' => 'Fusion Core Boutique',
            'description' => 'Pacchetto Fusion Core completo',
            'status' => 'attivo',
        ]);

        // 6. I TRE GIOIELLI
        $treGioielli = Contact::create([
            'name' => 'I Tre Gioielli',
            'email' => 'info@itregioielli.it',
            'phone' => '+39 349 1234987',
            'company' => 'I Tre Gioielli',
            'status' => 'cliente',
            'accordo_economico_mensile' => 27.00, // 81 trimestrale
            'dettagli_accordo' => 'Hosting e sito - Trimestrale 81€ bonifico',
            'potenziale_upsell' => 0,
            'note_upsell' => 'DA TAGLIARE - Cattiva cliente rompiballe. Marito ha ferramenta scontento con marketing attuale ma da evitare.',
            'esigenze_specifiche' => 'Prossima fattura: 1 marzo. ⚠️ Cliente problematica - valutare non rinnovo.',
            'settore_business' => 'Gioielleria',
            'canale_preferito' => 'email',
            'data_acquisizione' => Carbon::parse('2023-12-01'),
            'livello_soddisfazione' => 'insoddisfatto',
            'nps_score' => 3,
        ]);

        Project::create([
            'contact_id' => $treGioielli->id,
            'project_type_id' => $typeHosting->id,
            'name' => 'Hosting e Sito I Tre Gioielli',
            'description' => 'Hosting e manutenzione sito base',
            'status' => 'attivo',
        ]);

        // 7. STUDIO 3
        $studio3 = Contact::create([
            'name' => 'Studio 3',
            'email' => 'info@studio3.it',
            'phone' => '+39 335 6667778',
            'company' => 'Studio 3',
            'status' => 'cliente',
            'accordo_economico_mensile' => 39.17, // 470 annuale
            'dettagli_accordo' => 'Fusion Site - Annuale 470€ carta',
            'potenziale_upsell' => 100.00,
            'note_upsell' => 'Referenze possibili. Gestione base social a 100€/mese.',
            'esigenze_specifiche' => 'Prossima fattura: 5 dicembre. GHL: Basta Fusion.',
            'canale_preferito' => 'email',
            'data_acquisizione' => Carbon::parse('2023-12-05'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create([
            'contact_id' => $studio3->id,
            'project_type_id' => $typeFusionSite->id,
            'name' => 'Fusion Site Studio 3',
            'description' => 'Pacchetto Fusion Site annuale',
            'status' => 'attivo',
        ]);

        // 8. MAD FOR HAIR
        $madForHair = Contact::create([
            'name' => 'Mad for Hair',
            'email' => 'info@madforhair.it',
            'phone' => '+39 340 9998887',
            'company' => 'Mad for Hair',
            'status' => 'cliente',
            'accordo_economico_mensile' => 39.17, // 470 annuale
            'dettagli_accordo' => 'Fusion Site - Annuale 470€ carta/bonifico',
            'potenziale_upsell' => 500.00,
            'note_upsell' => 'Potenziale gestione social a 500€ o più al mese - cliente molto interessato.',
            'esigenze_specifiche' => 'Prossima fattura: 19/12/2026. GHL attivo. WhatsApp attivo.',
            'settore_business' => 'Parrucchieri',
            'canale_preferito' => 'whatsapp',
            'data_acquisizione' => Carbon::parse('2023-12-19'),
            'livello_soddisfazione' => 'molto_soddisfatto',
            'nps_score' => 9,
        ]);

        Project::create([
            'contact_id' => $madForHair->id,
            'project_type_id' => $typeFusionSite->id,
            'name' => 'Fusion Site Mad for Hair',
            'description' => 'Pacchetto Fusion Site annuale',
            'status' => 'attivo',
        ]);

        // 9. BELLEZZAPIÙ
        $bellezzaPiu = Contact::create([
            'name' => 'BellezzaPiù',
            'email' => 'info@bellezzapiu.it',
            'phone' => '+39 338 1112223',
            'company' => 'BellezzaPiù',
            'status' => 'cliente',
            'accordo_economico_mensile' => 150.00, // 300 bimensile
            'dettagli_accordo' => 'Fusion Core - Bimensile 300€ contanti',
            'potenziale_upsell' => 0,
            'note_upsell' => 'NO UPSELL - Gestisce bene lead formazione, fa tutto cartaceo con lead da 6000€ l\'uno. Potenziale % con WhatsApp marketing ma NON necessario.',
            'esigenze_specifiche' => 'Prossima fattura: 14 gennaio. GHL attivo. WhatsApp attivo. Cliente autonomo e organizzato.',
            'settore_business' => 'Formazione Estetica',
            'canale_preferito' => 'whatsapp',
            'data_acquisizione' => Carbon::parse('2024-01-14'),
            'livello_soddisfazione' => 'molto_soddisfatto',
            'nps_score' => 10,
        ]);

        Project::create([
            'contact_id' => $bellezzaPiu->id,
            'project_type_id' => $typeFusionCore->id,
            'name' => 'Fusion Core BellezzaPiù',
            'description' => 'Pacchetto Fusion Core completo',
            'status' => 'attivo',
        ]);

        // 10. STUDIO ALBANI 51
        $albani = Contact::create([
            'name' => 'Studio Albani 51',
            'email' => 'info@studioalbani51.it',
            'phone' => '+39 333 4445556',
            'company' => 'Studio Albani 51',
            'status' => 'cliente',
            'accordo_economico_mensile' => 0, // 15% deal chiusi
            'dettagli_accordo' => '15% deal chiusi - Pagamento dopo incasso bonifico',
            'potenziale_upsell' => 50.00,
            'note_upsell' => 'Sito quando scade a 30/50€/mese e/o Fusion Core.',
            'esigenze_specifiche' => 'Modello commissionale. WhatsApp attivo.',
            'settore_business' => 'Immobiliare',
            'canale_preferito' => 'whatsapp',
            'data_acquisizione' => Carbon::parse('2024-09-01'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create([
            'contact_id' => $albani->id,
            'project_type_id' => $typeSocial->id,
            'name' => 'Lead Generation Immobiliare',
            'description' => 'Generazione lead con modello commissionale 15%',
            'status' => 'attivo',
        ]);

        // 11. VISMARA
        $vismara = Contact::create([
            'name' => 'Vismara',
            'email' => 'info@vismara.it',
            'phone' => '+39 339 7776665',
            'company' => 'Vismara Srl',
            'status' => 'cliente',
            'accordo_economico_mensile' => 416.67, // 1250 trimestrale
            'dettagli_accordo' => 'Gestionale custom (1250€ trimestrale) + Server (450€ annuale)',
            'potenziale_upsell' => 1000.00,
            'note_upsell' => 'Sito web a 1000€ o altri gestionali sempre a 1000€. NO altro ricorrente (già alto). Possibili referenze di altre ditte per gestionali.',
            'esigenze_specifiche' => 'Prossima fattura: 1 aprile (gestionale) e 01/07/2025 (server). Cliente strategico per referenze B2B.',
            'settore_business' => 'Industria/Produzione',
            'canale_preferito' => 'email',
            'data_acquisizione' => Carbon::parse('2023-04-01'),
            'livello_soddisfazione' => 'molto_soddisfatto',
            'nps_score' => 9,
        ]);

        Project::create([
            'contact_id' => $vismara->id,
            'project_type_id' => $typeGestionale->id,
            'name' => 'Gestionale Vismara',
            'description' => 'Software gestionale custom per produzione',
            'status' => 'attivo',
        ]);

        Project::create([
            'contact_id' => $vismara->id,
            'project_type_id' => $typeHosting->id,
            'name' => 'Server Dedicato Vismara',
            'description' => 'Gestione server dedicato',
            'status' => 'attivo',
        ]);

        // 12. DEA BAGS
        $deaBags = Contact::create([
            'name' => 'Dea Bags',
            'email' => 'info@deabags.it',
            'phone' => '+39 345 8887776',
            'company' => 'Dea Bags',
            'status' => 'cliente',
            'accordo_economico_mensile' => 100.00,
            'dettagli_accordo' => 'Assistenza e-commerce - Mensile 100€ bonifico',
            'potenziale_upsell' => 500.00,
            'note_upsell' => 'Chiedere referenze per altri e-commerce. Potenziale deal a 300/500€/mese per servizi ManyChat chatbot AI per aumentare vendite e gestione campagne Meta.',
            'esigenze_specifiche' => 'Focus su automazione vendite e-commerce.',
            'settore_business' => 'E-commerce Moda',
            'canale_preferito' => 'email',
            'data_acquisizione' => Carbon::parse('2024-05-20'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create([
            'contact_id' => $deaBags->id,
            'project_type_id' => $typeWeb->id,
            'name' => 'E-commerce Dea Bags',
            'description' => 'Assistenza e manutenzione e-commerce',
            'status' => 'attivo',
        ]);

        // 13. ARIEL
        $ariel = Contact::create([
            'name' => 'Ariel',
            'email' => 'info@arielbeauty.it',
            'phone' => '+39 340 6665554',
            'company' => 'Ariel Beauty',
            'status' => 'cliente',
            'accordo_economico_mensile' => 47.00,
            'dettagli_accordo' => 'Fusion Site - Mensile 47€ carta',
            'potenziale_upsell' => 50.00,
            'note_upsell' => 'Gestionale ha detto che le sarebbe utile a 30/50€/mese. Interessante più che altro perché poi posso entrare nel mondo dei gestionali estetici.',
            'esigenze_specifiche' => 'Prossima fattura: 2 febbraio. Cliente strategico per sviluppo gestionali estetici.',
            'settore_business' => 'Estetica',
            'canale_preferito' => 'email',
            'data_acquisizione' => Carbon::parse('2024-10-02'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create([
            'contact_id' => $ariel->id,
            'project_type_id' => $typeFusionSite->id,
            'name' => 'Fusion Site Ariel',
            'description' => 'Pacchetto Fusion Site mensile',
            'status' => 'attivo',
        ]);

        // ==================== TASK ESEMPIO ====================
        
        // Task per Dental Equipe (cliente interessato a upsell)
        $projectDental = Project::where('contact_id', $dentalEquipe->id)->first();
        Task::create([
            'project_id' => $projectDental->id,
            'task_template_id' => $templateReport->id,
            'name' => 'Inviare proposta piano 2026',
            'description' => 'Follow-up proposta WhatsApp marketing (150€) + Social (100€)',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(3),
            'priority' => 'alta',
            'priority_score' => 85,
            'estimated_hours' => 2,
            'tags' => json_encode(['upsell', 'urgente']),
            'category' => 'vendita',
        ]);

        // Task per Vismara (referenze)
        $projectVismara = Project::where('contact_id', $vismara->id)->where('name', 'LIKE', '%Gestionale%')->first();
        Task::create([
            'project_id' => $projectVismara->id,
            'task_template_id' => $templateReport->id,
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

        // Task ricorrente per BellezzaPiù
        $projectBellezza = Project::where('contact_id', $bellezzaPiu->id)->first();
        Task::create([
            'project_id' => $projectBellezza->id,
            'task_template_id' => $templateManutenzione->id,
            'name' => 'Check-in mensile cliente',
            'description' => 'Verifica soddisfazione e funzionamento sistema',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(14),
            'is_recurring' => true,
            'recurrence_pattern' => 'monthly',
            'recurrence_interval' => 1,
            'priority' => 'media',
            'priority_score' => 60,
            'estimated_hours' => 1,
            'tags' => json_encode(['check-in', 'customer-success']),
            'category' => 'supporto',
        ]);

        // Task per Mad for Hair (grande opportunità)
        $projectMad = Project::where('contact_id', $madForHair->id)->first();
        Task::create([
            'project_id' => $projectMad->id,
            'task_template_id' => $templateReport->id,
            'name' => 'Proposta gestione social 500€/mese',
            'description' => 'Preparare proposta dettagliata per gestione social completa',
            'status' => 'in_corso',
            'deadline' => Carbon::now()->addDays(5),
            'started_at' => Carbon::now(),
            'priority' => 'critica',
            'priority_score' => 95,
            'estimated_hours' => 4,
            'tags' => json_encode(['upsell', 'alto-valore', 'urgente']),
            'category' => 'vendita',
        ]);

        // Task per I Tre Gioielli (valutare non rinnovo)
        $projectGioielli = Project::where('contact_id', $treGioielli->id)->first();
        Task::create([
            'project_id' => $projectGioielli->id,
            'name' => 'Valutare non rinnovo contratto',
            'description' => 'Cliente problematica - decidere se rinnovare a marzo. Preparare comunicazione.',
            'status' => 'da_fare',
            'deadline' => Carbon::parse('2026-02-15'),
            'priority' => 'bassa',
            'priority_score' => 40,
            'estimated_hours' => 1,
            'tags' => json_encode(['admin', 'cliente-problematico']),
            'category' => 'admin',
        ]);
        */
    }
}
