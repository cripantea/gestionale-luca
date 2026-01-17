<?php

namespace Database\Seeders;

use App\Models\Spesa;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SpeseSeeder extends Seeder
{
    public function run(): void
    {
        $spese = [
            [
                'nome' => 'Canva',
                'importo_totale' => 150.00,
                'frequenza' => 'annuale',
                'descrizione' => '15 ottobre',
                'data_prossimo_rinnovo' => Carbon::parse('2026-10-15'),
            ],
            [
                'nome' => 'Domini',
                'importo_totale' => 20.00,
                'frequenza' => 'mensile',
                'descrizione' => '9 domini',
                'data_prossimo_rinnovo' => Carbon::now()->startOfMonth()->addMonth(),
            ],
            [
                'nome' => 'Qbox Mail',
                'importo_totale' => 13.00,
                'frequenza' => 'mensile',
                'descrizione' => '1 del mese',
                'data_prossimo_rinnovo' => Carbon::now()->startOfMonth()->addMonth(),
            ],
            [
                'nome' => 'Iubenda',
                'importo_totale' => 4.00,
                'frequenza' => 'mensile',
                'descrizione' => 'Ogni mese',
                'data_prossimo_rinnovo' => Carbon::now()->startOfMonth()->addMonth(),
            ],
            [
                'nome' => 'Tutor LMS',
                'importo_totale' => 120.00,
                'frequenza' => 'annuale',
                'descrizione' => '17/07/2027',
                'data_prossimo_rinnovo' => Carbon::parse('2027-07-17'),
            ],
            [
                'nome' => 'Fiscozen',
                'importo_totale' => 500.00,
                'frequenza' => 'annuale',
                'descrizione' => '11 ottobre',
                'data_prossimo_rinnovo' => Carbon::parse('2026-10-11'),
            ],
            [
                'nome' => 'Elementor',
                'importo_totale' => 120.00,
                'frequenza' => 'annuale',
                'descrizione' => '29 settembre',
                'data_prossimo_rinnovo' => Carbon::parse('2026-09-29'),
            ],
            [
                'nome' => 'WhatsApp Business',
                'importo_totale' => 60.00,
                'frequenza' => 'mensile',
                'descrizione' => '6 account',
                'data_prossimo_rinnovo' => Carbon::now()->startOfMonth()->addMonth(),
            ],
            [
                'nome' => 'Iliad',
                'importo_totale' => 6.00,
                'frequenza' => 'mensile',
                'descrizione' => '4 SIM',
                'data_prossimo_rinnovo' => Carbon::now()->startOfMonth()->addMonth(),
            ],
            [
                'nome' => 'Twilio',
                'importo_totale' => 1.00,
                'frequenza' => 'mensile',
                'descrizione' => 'Numero di telefono',
                'data_prossimo_rinnovo' => Carbon::now()->startOfMonth()->addMonth(),
            ],
            [
                'nome' => 'GoHighLevel',
                'importo_totale' => 300.00,
                'frequenza' => 'annuale',
                'descrizione' => 'Piattaforma automazione',
                'data_prossimo_rinnovo' => Carbon::now()->addYear(),
            ],
            [
                'nome' => 'CapCut',
                'importo_totale' => 100.00,
                'frequenza' => 'annuale',
                'descrizione' => 'Dicembre',
                'data_prossimo_rinnovo' => Carbon::parse('2026-12-01'),
            ],
        ];

        foreach ($spese as $spesa) {
            // Calcola importo mensile
            $importoMensile = match($spesa['frequenza']) {
                'mensile' => $spesa['importo_totale'],
                'bimensile' => $spesa['importo_totale'] / 2,
                'trimestrale' => $spesa['importo_totale'] / 3,
                'annuale' => $spesa['importo_totale'] / 12,
                default => 0,
            };

            Spesa::create([
                'nome' => $spesa['nome'],
                'importo_totale' => $spesa['importo_totale'],
                'importo_mensile' => $importoMensile,
                'frequenza' => $spesa['frequenza'],
                'descrizione' => $spesa['descrizione'],
                'data_prossimo_rinnovo' => $spesa['data_prossimo_rinnovo'],
                'attiva' => true,
            ]);
        }
    }
}
