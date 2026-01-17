<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RealSubscriptionsSeeder extends Seeder
{
    public function run(): void
    {
        // IMPORTANTE: Cancella tutti i contratti esistenti
        Subscription::query()->delete();

        // MDL UNIPOL - 2 contratti
        $mdlUnipol = Contact::where('name', 'MDL Unipol')->first();
        if ($mdlUnipol) {
            // Contratto 1: Social e Sito - 400€/mese
            Subscription::create([
                'contact_id' => $mdlUnipol->id,
                'nome' => 'Gestione social e sito',
                'importo_contratto' => 400.00,
                'frequenza' => 'mensile',
                'mrr_calcolato' => 400.00,
                'data_prossima_fattura' => Carbon::parse('2026-01-08'),
                'metodo_pagamento' => 'bonifico',
                'attivo' => true,
            ]);

            // Contratto 2: Gestionale Sinistri - 600€/anno
            Subscription::create([
                'contact_id' => $mdlUnipol->id,
                'nome' => 'Gestionale Sinistri',
                'importo_contratto' => 600.00,
                'frequenza' => 'annuale',
                'mrr_calcolato' => 50.00, // 600 / 12
                'data_prossima_fattura' => Carbon::parse('2026-07-01'),
                'metodo_pagamento' => 'bonifico',
                'attivo' => true,
            ]);
        }

        // FIORELLA RIGAMONTI - 100€/mese
        $fiorella = Contact::where('name', 'Fiorella Rigamonti')->first();
        if ($fiorella) {
            Subscription::create([
                'contact_id' => $fiorella->id,
                'nome' => 'Sito web',
                'importo_contratto' => 100.00,
                'frequenza' => 'mensile',
                'mrr_calcolato' => 100.00,
                'data_prossima_fattura' => Carbon::parse('2026-01-30'),
                'metodo_pagamento' => 'bonifico',
                'attivo' => true,
            ]);
        }

        // FAIRY TOOTH - 2 contratti
        $fairyTooth = Contact::where('name', 'Fairy Tooth')->first();
        if ($fairyTooth) {
            // Contratto 1: Sito web + report - 200€ bimensile
            Subscription::create([
                'contact_id' => $fairyTooth->id,
                'nome' => 'Sito web + report + Blog mensile',
                'importo_contratto' => 200.00,
                'frequenza' => 'bimensile',
                'mrr_calcolato' => 100.00, // 200 / 2
                'data_prossima_fattura' => Carbon::parse('2026-02-04'),
                'metodo_pagamento' => 'contanti',
                'attivo' => true,
            ]);

            // Contratto 2: Deal percentuale - 20% sopra i 2 corsi
            Subscription::create([
                'contact_id' => $fairyTooth->id,
                'nome' => '20% sopra i 2 corsi venduti ogni mese',
                'importo_contratto' => 0,
                'frequenza' => 'percentuale',
                'mrr_calcolato' => 0,
                'percentuale' => 20.00,
                'condizioni_percentuale' => 'sopra i 2 corsi venduti ogni mese',
                'metodo_pagamento' => 'contanti',
                'attivo' => true,
            ]);
        }

        // DENTAL EQUIPE - 150€/mese
        $dentalEquipe = Contact::where('name', 'Dental Equipe')->first();
        if ($dentalEquipe) {
            Subscription::create([
                'contact_id' => $dentalEquipe->id,
                'nome' => 'Fusion Core',
                'importo_contratto' => 150.00,
                'frequenza' => 'mensile',
                'mrr_calcolato' => 150.00,
                'data_prossima_fattura' => Carbon::parse('2026-02-01'),
                'metodo_pagamento' => 'carta',
                'attivo' => true,
            ]);
        }

        // LA BOUTIQUE DEL BENESSERE - 150€/mese
        $boutique = Contact::where('name', 'La Boutique del Benessere')->first();
        if ($boutique) {
            Subscription::create([
                'contact_id' => $boutique->id,
                'nome' => 'Fusion Core',
                'importo_contratto' => 150.00,
                'frequenza' => 'mensile',
                'mrr_calcolato' => 150.00,
                'data_prossima_fattura' => Carbon::parse('2026-01-25'),
                'metodo_pagamento' => 'carta',
                'attivo' => true,
            ]);
        }

        // I TRE GIOIELLI - 81€ trimestrale
        $treGioielli = Contact::where('name', 'I Tre Gioielli')->first();
        if ($treGioielli) {
            Subscription::create([
                'contact_id' => $treGioielli->id,
                'nome' => 'hosting e sito',
                'importo_contratto' => 81.00,
                'frequenza' => 'trimestrale',
                'mrr_calcolato' => 27.00, // 81 / 3
                'data_prossima_fattura' => Carbon::parse('2026-03-01'),
                'metodo_pagamento' => 'bonifico',
                'attivo' => true,
            ]);
        }

        // STUDIO 3 - 470€ annuale
        $studio3 = Contact::where('name', 'Studio 3')->first();
        if ($studio3) {
            Subscription::create([
                'contact_id' => $studio3->id,
                'nome' => 'Fusion site',
                'importo_contratto' => 470.00,
                'frequenza' => 'annuale',
                'mrr_calcolato' => 39.17, // 470 / 12
                'data_prossima_fattura' => Carbon::parse('2026-12-05'),
                'metodo_pagamento' => 'carta',
                'attivo' => true,
            ]);
        }

        // MAD FOR HAIR - 470€ annuale
        $madForHair = Contact::where('name', 'Mad for Hair')->first();
        if ($madForHair) {
            Subscription::create([
                'contact_id' => $madForHair->id,
                'nome' => 'Fusion Site',
                'importo_contratto' => 470.00,
                'frequenza' => 'annuale',
                'mrr_calcolato' => 39.17, // 470 / 12
                'data_prossima_fattura' => Carbon::parse('2026-12-19'),
                'metodo_pagamento' => 'carta',
                'attivo' => true,
            ]);
        }

        // BELLEZZAPIÙ - 300€ bimensile
        $bellezzapiu = Contact::where('name', 'BellezzaPiù')->first();
        if ($bellezzapiu) {
            Subscription::create([
                'contact_id' => $bellezzapiu->id,
                'nome' => 'fusion core',
                'importo_contratto' => 300.00,
                'frequenza' => 'bimensile',
                'mrr_calcolato' => 150.00, // 300 / 2
                'data_prossima_fattura' => Carbon::parse('2026-01-14'),
                'metodo_pagamento' => 'contanti',
                'attivo' => true,
            ]);
        }

        // STUDIO ALBANI 51 - Deal percentuale 15%
        $studioAlbani = Contact::where('name', 'Studio Albani 51')->first();
        if ($studioAlbani) {
            Subscription::create([
                'contact_id' => $studioAlbani->id,
                'nome' => '15% deal chiusi',
                'importo_contratto' => 0,
                'frequenza' => 'percentuale',
                'mrr_calcolato' => 0,
                'percentuale' => 15.00,
                'condizioni_percentuale' => 'dopo pagamento',
                'metodo_pagamento' => 'bonifico',
                'attivo' => true,
            ]);
        }

        // VISMARA - 2 contratti
        $vismara = Contact::where('name', 'Vismara')->first();
        if ($vismara) {
            // Contratto 1: Gestionale - 1250€ trimestrale
            Subscription::create([
                'contact_id' => $vismara->id,
                'nome' => 'Gestionale',
                'importo_contratto' => 1250.00,
                'frequenza' => 'trimestrale',
                'mrr_calcolato' => 416.67, // 1250 / 3
                'data_prossima_fattura' => Carbon::parse('2026-04-01'),
                'metodo_pagamento' => 'bonifico',
                'attivo' => true,
            ]);

            // Contratto 2: Server - 450€ annuale (SCADUTO!)
            Subscription::create([
                'contact_id' => $vismara->id,
                'nome' => 'server',
                'importo_contratto' => 450.00,
                'frequenza' => 'annuale',
                'mrr_calcolato' => 37.50, // 450 / 12
                'data_prossima_fattura' => Carbon::parse('2025-07-01'), // NOTA: è scaduto!
                'metodo_pagamento' => 'bonifico',
                'attivo' => true,
            ]);
        }

        // DEA BAGS - 100€/mese
        $deaBags = Contact::where('name', 'Dea Bags')->first();
        if ($deaBags) {
            Subscription::create([
                'contact_id' => $deaBags->id,
                'nome' => 'assitenza ecommerce',
                'importo_contratto' => 100.00,
                'frequenza' => 'mensile',
                'mrr_calcolato' => 100.00,
                'data_prossima_fattura' => null, // da definire
                'metodo_pagamento' => 'bonifico',
                'attivo' => true,
            ]);
        }

        // ARIEL - 47€/mese
        $ariel = Contact::where('name', 'Ariel')->first();
        if ($ariel) {
            Subscription::create([
                'contact_id' => $ariel->id,
                'nome' => 'fusion site',
                'importo_contratto' => 47.00,
                'frequenza' => 'mensile',
                'mrr_calcolato' => 47.00,
                'data_prossima_fattura' => Carbon::parse('2026-02-02'),
                'metodo_pagamento' => 'carta',
                'attivo' => true,
            ]);
        }

        $this->command->info('✅ Contratti creati con successo!');
        
        // Statistiche
        $totalContratti = Subscription::count();
        $totalMrr = Subscription::where('attivo', true)->sum('mrr_calcolato');
        $contrattiPercentuale = Subscription::where('frequenza', 'percentuale')->count();
        
        $this->command->info("📊 Totale contratti: {$totalContratti}");
        $this->command->info("💰 MRR Totale: " . number_format($totalMrr, 2) . "€");
        $this->command->info("📈 Contratti a percentuale: {$contrattiPercentuale}");
    }
}
