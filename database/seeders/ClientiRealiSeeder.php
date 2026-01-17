<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ClientiRealiSeeder extends Seeder
{
    public function run(): void
    {
        $typeWeb = ProjectType::where('name', 'Sito Web')->first();
        $typeSocial = ProjectType::where('name', 'Gestione Social')->first();
        $typeGestionale = ProjectType::where('name', 'Gestionale Custom')->first();
        $typeFusionCore = ProjectType::where('name', 'Fusion Core')->first();
        $typeFusionSite = ProjectType::where('name', 'Fusion Site')->first();
        $typeHosting = ProjectType::where('name', 'Hosting & Server')->first();

        // 1. MDL UNIPOL - 2 contratti: 400€/mese + 600€/anno (50€/mese) = 450€ MRR
        $mdlUnipol = Contact::create([
            'name' => 'MDL Unipol',
            'company' => 'MDL Unipol',
            'status' => 'cliente',
            'accordo_economico_mensile' => 450.00, // 400 mensile + 50 mensile (600 annuale)
            'tipo_fatturazione' => 'mensile',
            'metodo_pagamento' => 'bonifico',
            'data_prossima_fattura' => Carbon::parse('2026-01-08'),
            'ghl_attivo' => false,
            'whatsapp_attivo' => false,
            'dettagli_accordo' => 'Gestione social e sito: 400€/mese. Gestionale Sinistri: 600€/anno (prossima: 1 luglio)',
            'potenziale_upsell' => 300.00,
            'note_upsell' => 'WhatsApp marketing 100€/mese, sfruttare base contatti per %, diventare subagente responsabile investimenti 200€/mese (2 giorni/settimana)',
            'esigenze_specifiche' => '⚠️ DUE CONTRATTI SEPARATI: Social mensile (8 gennaio) + Gestionale annuale (1 luglio)',
            'settore_business' => 'Assicurazioni',
            'data_acquisizione' => Carbon::parse('2024-01-15'),
            'livello_soddisfazione' => 'soddisfatto',
            'nps_score' => 8,
        ]);

        Project::create(['contact_id' => $mdlUnipol->id, 'project_type_id' => $typeSocial->id, 'name' => 'Gestione Social + Sito (400€/mese)', 'description' => 'Fatturazione mensile', 'status' => 'attivo']);
        Project::create(['contact_id' => $mdlUnipol->id, 'project_type_id' => $typeGestionale->id, 'name' => 'Gestionale Sinistri (600€/anno)', 'description' => 'Fatturazione annuale - prossima: 1 luglio', 'status' => 'attivo']);

        // 2. VISMARA - 2 contratti: 1250€/trim (416.67€/mese) + 450€/anno (37.5€/mese) = 454.17€ MRR
        $vismara = Contact::create([
            'name' => 'Vismara',
            'company' => 'Vismara Srl',
            'status' => 'cliente',
            'accordo_economico_mensile' => 454.17, // 416.67 gestionale + 37.5 server
            'tipo_fatturazione' => 'trimestrale',
            'metodo_pagamento' => 'bonifico',
            'data_prossima_fattura' => Carbon::parse('2026-04-01'),
            'ghl_attivo' => false,
            'whatsapp_attivo' => false,
            'dettagli_accordo' => 'Gestionale custom: 1250€/trimestre. Server dedicato: 450€/anno (prossima: 1 luglio 2025)',
            'potenziale_upsell' => 1000.00,
            'note_upsell' => 'Sito web a 1000€ una tantum o altri gestionali sempre a 1000€. NO altro ricorrente (già alto). STRATEGICO: possibili referenze di altre ditte per gestionali B2B.',
            'esigenze_specifiche' => '⚠️ DUE CONTRATTI: Gestionale trim. + Server annuale. Cliente PREMIUM per referenze B2B.',
            'settore_business' => 'Industria/Produzione',
            'data_acquisizione' => Carbon::parse('2023-04-01'),
            'livello_soddisfazione' => 'molto_soddisfatto',
            'nps_score' => 9,
        ]);

        Project::create(['contact_id' => $vismara->id, 'project_type_id' => $typeGestionale->id, 'name' => 'Gestionale Produzione (1250€/trim)', 'status' => 'attivo']);
        Project::create(['contact_id' => $vismara->id, 'project_type_id' => $typeHosting->id, 'name' => 'Server Dedicato (450€/anno)', 'status' => 'attivo']);

        Task::create([
            'project_id' => Project::where('contact_id', $vismara->id)->where('name', 'LIKE', '%Gestionale%')->first()->id,
            'name' => '🤝 Richiedere referenze B2B',
            'description' => 'Cliente strategico - chiedere referenze per altre aziende interessate a gestionali custom',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(7),
            'priority' => 'media',
            'priority_score' => 70,
            'estimated_hours' => 1,
            'tags' => json_encode(['referenze', 'business-development', 'b2b']),
            'category' => 'vendita',
        ]);

        // 3. DENTAL EQUIPE - 150€/mese
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
            'dettagli_accordo' => 'Fusion Core 150€/mese - carta/bonifico',
            'potenziale_upsell' => 250.00,
            'note_upsell' => '🔥 CALDO: WhatsApp marketing a 150€ + social a 100€ = 250€/mese. Piano 2026 GIÀ INVIATO - cliente INTERESSATO. Follow-up urgente!',
            'esigenze_specifiche' => 'GHL attivo, WhatsApp attivo. Cliente molto soddisfatto - ottimo per upsell.',
            'settore_business' => 'Odontoiatria',
            'data_acquisizione' => Carbon::parse('2024-02-01'),
            'livello_soddisfazione' => 'molto_soddisfatto',
            'nps_score' => 9,
        ]);

        $projectDental = Project::create(['contact_id' => $dentalEquipe->id, 'project_type_id' => $typeFusionCore->id, 'name' => 'Fusion Core', 'status' => 'attivo']);

        Task::create([
            'project_id' => $projectDental->id,
            'name' => '🔥 URGENTE: Follow-up Piano 2026',
            'description' => 'Contattare per chiudere upsell WhatsApp (150€) + Social (100€) - piano già inviato, cliente interessato',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(3),
            'priority' => 'alta',
            'priority_score' => 85,
            'estimated_hours' => 2,
            'tags' => json_encode(['upsell', 'urgente', 'hot-lead', 'chiusura']),
            'category' => 'vendita',
        ]);

        // 4. LA BOUTIQUE DEL BENESSERE - 150€/mese
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
            'dettagli_accordo' => 'Fusion Core 150€/mese - carta',
            'potenziale_upsell' => 50.00,
            'note_upsell' => 'Potenziale gestionale appuntamenti automatico 30-50€/mese, ma poco tecniche - da valutare con cautela.',
            'esigenze_specifiche' => 'GHL attivo, WhatsApp attivo. Cliente soddisfatto ma non tech-savvy.',
            'settore_business' => 'Wellness & Beauty',
            'data_acquisizione' => Carbon::parse('2024-06-15'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create(['contact_id' => $boutique->id, 'project_type_id' => $typeFusionCore->id, 'name' => 'Fusion Core', 'status' => 'attivo']);

        // 5. BELLEZZAPIÙ - 150€/mese (300€ bimensile)
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
            'dettagli_accordo' => 'Fusion Core 300€/bimestre - contanti',
            'potenziale_upsell' => 0,
            'note_upsell' => '✅ CLIENTE PERFETTO - NO UPSELL NECESSARIO. Lead da 6000€, gestisce tutto perfettamente con cartaceo. NPS 10. MANTENERE SODDISFAZIONE.',
            'esigenze_specifiche' => '⭐ CLIENTE TOP - NPS 10. Formazione estetica, lead altissimo valore. Focus su retention.',
            'settore_business' => 'Formazione Estetica',
            'data_acquisizione' => Carbon::parse('2024-01-14'),
            'livello_soddisfazione' => 'molto_soddisfatto',
            'nps_score' => 10,
        ]);

        $projectBellezza = Project::create(['contact_id' => $bellezzaPiu->id, 'project_type_id' => $typeFusionCore->id, 'name' => 'Fusion Core', 'status' => 'attivo']);

        Task::create([
            'project_id' => $projectBellezza->id,
            'name' => '⭐ Check-in VIP Cliente NPS 10',
            'description' => 'Verifica soddisfazione cliente TOP - mantenere relazione',
            'status' => 'da_fare',
            'deadline' => Carbon::now()->addDays(14),
            'is_recurring' => true,
            'recurrence_pattern' => 'monthly',
            'recurrence_interval' => 1,
            'priority' => 'media',
            'priority_score' => 60,
            'estimated_hours' => 1,
            'tags' => json_encode(['check-in', 'vip-client', 'retention']),
            'category' => 'supporto',
        ]);

        // 6. FIORELLA RIGAMONTI - 100€/mese
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
            'dettagli_accordo' => 'Sito web 100€/mese - bonifico',
            'potenziale_upsell' => 200.00,
            'note_upsell' => 'WhatsApp marketing 200€/mese O sfruttare base contatti per %. Da proporre quando pronta.',
            'esigenze_specifiche' => 'Sito web base. Potenziale per automazioni.',
            'data_acquisizione' => Carbon::parse('2024-08-20'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create(['contact_id' => $fiorella->id, 'project_type_id' => $typeWeb->id, 'name' => 'Sito Web', 'status' => 'attivo']);

        // 7. FAIRY TOOTH - 100€/mese (200€ bimensile) + 20% corsi
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
            'dettagli_accordo' => 'Sito + Report + Blog mensile: 200€/bimestre (contanti). PLUS: 20% sopra i 2 corsi venduti ogni mese.',
            'potenziale_upsell' => 0,
            'note_upsell' => 'GHL: Basta Fusion. NO upsell diretto, ma FOCUS SU REFERENZE - cliente soddisfatto.',
            'esigenze_specifiche' => 'Modello ibrido: fisso + commissioni. Ottimo per case study.',
            'settore_business' => 'Odontoiatria',
            'data_acquisizione' => Carbon::parse('2024-03-10'),
            'livello_soddisfazione' => 'molto_soddisfatto',
        ]);

        Project::create(['contact_id' => $fairyTooth->id, 'project_type_id' => $typeWeb->id, 'name' => 'Sito + Report + Blog', 'status' => 'attivo']);

        // 8. DEA BAGS - 100€/mese
        $deaBags = Contact::create([
            'name' => 'Dea Bags',
            'company' => 'Dea Bags E-commerce',
            'status' => 'cliente',
            'accordo_economico_mensile' => 100.00,
            'tipo_fatturazione' => 'mensile',
            'metodo_pagamento' => 'bonifico',
            'data_prossima_fattura' => Carbon::now()->addDays(15),
            'ghl_attivo' => false,
            'whatsapp_attivo' => false,
            'dettagli_accordo' => 'Assistenza e-commerce 100€/mese - bonifico',
            'potenziale_upsell' => 500.00,
            'note_upsell' => '💎 ALTA OPPORTUNITÀ: ManyChat chatbot AI + gestione campagne Meta 300-500€/mese. INOLTRE: chiedere referenze per altri e-commerce.',
            'esigenze_specifiche' => 'E-commerce moda. Ottimo per automazioni vendite e referenze settore.',
            'settore_business' => 'E-commerce Moda',
            'data_acquisizione' => Carbon::parse('2024-05-20'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create(['contact_id' => $deaBags->id, 'project_type_id' => $typeWeb->id, 'name' => 'E-commerce + Assistenza', 'status' => 'attivo']);

        // 9. ARIEL - 47€/mese
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
            'dettagli_accordo' => 'Fusion Site 47€/mese - carta',
            'potenziale_upsell' => 50.00,
            'note_upsell' => '🎯 STRATEGICO: Gestionale estetico 30-50€/mese. Cliente ha detto che le sarebbe UTILE. IMPORTANTE: porta d\'ingresso nel mondo dei gestionali estetici.',
            'esigenze_specifiche' => 'Cliente strategico per sviluppo verticale gestionali estetici. Opportunità di mercato.',
            'settore_business' => 'Estetica',
            'data_acquisizione' => Carbon::parse('2024-10-02'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create(['contact_id' => $ariel->id, 'project_type_id' => $typeFusionSite->id, 'name' => 'Fusion Site', 'status' => 'attivo']);

        // 10. MAD FOR HAIR - 39.17€/mese (470€/anno)
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
            'dettagli_accordo' => 'Fusion Site 470€/anno - carta/bonifico',
            'potenziale_upsell' => 500.00,
            'note_upsell' => '🔥🔥🔥 PRIORITÀ MASSIMA: Gestione social 500€+/mese - cliente MOLTO INTERESSATO. GHL e WhatsApp già attivi. Cliente NPS 9. CHIUDERE URGENTEMENTE!',
            'esigenze_specifiche' => '⚡ OPPORTUNITÀ TOP - GHL attivo, WhatsApp attivo, NPS 9. Ready per upsell massivo.',
            'settore_business' => 'Parrucchieri',
            'data_acquisizione' => Carbon::parse('2023-12-19'),
            'livello_soddisfazione' => 'molto_soddisfatto',
            'nps_score' => 9,
        ]);

        $projectMad = Project::create(['contact_id' => $madForHair->id, 'project_type_id' => $typeFusionSite->id, 'name' => 'Fusion Site', 'status' => 'attivo']);

        Task::create([
            'project_id' => $projectMad->id,
            'name' => '💎 PRIORITÀ 1: Proposta Social 500€',
            'description' => 'Preparare e inviare proposta dettagliata gestione social completa - cliente MOLTO interessato. Priorità assoluta!',
            'status' => 'in_corso',
            'started_at' => Carbon::now(),
            'deadline' => Carbon::now()->addDays(5),
            'priority' => 'critica',
            'priority_score' => 95,
            'estimated_hours' => 4,
            'tags' => json_encode(['upsell', 'alto-valore', 'urgente', 'hot-lead', 'priorita-1']),
            'category' => 'vendita',
        ]);

        // 11. STUDIO 3 - 39.17€/mese (470€/anno)
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
            'dettagli_accordo' => 'Fusion Site 470€/anno - carta. GHL: Basta Fusion',
            'potenziale_upsell' => 100.00,
            'note_upsell' => 'Gestione base social 100€/mese. Possibili referenze. Approccio soft.',
            'esigenze_specifiche' => 'GHL: Basta Fusion. Focus su referenze più che upsell.',
            'data_acquisizione' => Carbon::parse('2023-12-05'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create(['contact_id' => $studio3->id, 'project_type_id' => $typeFusionSite->id, 'name' => 'Fusion Site', 'status' => 'attivo']);

        // 12. I TRE GIOIELLI - 27€/mese (81€/trimestre)
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
            'dettagli_accordo' => 'Hosting + sito 81€/trimestre - bonifico',
            'potenziale_upsell' => 0,
            'note_upsell' => '⚠️ DA TAGLIARE - Cliente problematica ROMPIBALLE. Marito ha ferramenta scontento con attività marketing attuale ma DA EVITARE. NON rinnovare.',
            'esigenze_specifiche' => '🚫 CLIENTE NEGATIVO - Valutare non rinnovo contratto a marzo. NPS 3.',
            'settore_business' => 'Gioielleria',
            'data_acquisizione' => Carbon::parse('2023-12-01'),
            'livello_soddisfazione' => 'insoddisfatto',
            'nps_score' => 3,
        ]);

        $projectGioielli = Project::create(['contact_id' => $treGioielli->id, 'project_type_id' => $typeHosting->id, 'name' => 'Hosting + Sito', 'status' => 'attivo']);

        Task::create([
            'project_id' => $projectGioielli->id,
            'name' => '⚠️ NON RINNOVARE marzo',
            'description' => 'Cliente problematica - preparare comunicazione non rinnovo contratto (scade marzo)',
            'status' => 'da_fare',
            'deadline' => Carbon::parse('2026-02-15'),
            'priority' => 'bassa',
            'priority_score' => 40,
            'estimated_hours' => 1,
            'tags' => json_encode(['admin', 'cliente-problematico', 'non-rinnovo']),
            'category' => 'admin',
        ]);

        // 13. STUDIO ALBANI 51 - 0€/mese (15% commissionale)
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
            'dettagli_accordo' => '15% deal chiusi - pagamento dopo incasso bonifico',
            'potenziale_upsell' => 50.00,
            'note_upsell' => 'Sito quando scade a 30-50€/mese e/o Fusion Core. Modello commissionale funziona, ma espandibile.',
            'esigenze_specifiche' => 'Modello commissionale 15%. WhatsApp attivo. Immobiliare.',
            'settore_business' => 'Immobiliare',
            'data_acquisizione' => Carbon::parse('2024-09-01'),
            'livello_soddisfazione' => 'soddisfatto',
        ]);

        Project::create(['contact_id' => $albani->id, 'project_type_id' => $typeSocial->id, 'name' => 'Lead Generation Immobiliare', 'status' => 'attivo']);
    }
}
