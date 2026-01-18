<?php

namespace App\Http\Controllers;

use App\Models\Spesa;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SpesaController extends Controller
{
    public function index(): Response
    {
        $spese = Spesa::orderBy('attiva', 'desc')
            ->orderBy('importo_mensile', 'desc')
            ->get();
        
        $totaleRicorrenti = Spesa::attive()->ricorrenti()->sum('importo_mensile');
        $totaleUnaTantum = Spesa::attive()->unaTantum()->sum('importo_totale');
        
        // Allineato con logica Dashboard
        // MRR Lordo: somma di TUTTI i contratti attivi (esclusi percentuali)
        $mrrLordo = \App\Models\Subscription::where('attivo', true)
            ->where('frequenza', '!=', 'percentuale')
            ->sum('mrr_calcolato');
        
        // MRR Netto (togli 25% contributi regime forfettario)
        $mrrNetto = $mrrLordo * 0.75;
        
        // Utile (MRR Netto - Spese)
        $utile = $mrrNetto - $totaleRicorrenti;
        
        $stats = [
            'totale_mensile' => $totaleRicorrenti,
            'totale_annuale' => $totaleRicorrenti * 12,
            'numero_spese' => Spesa::attive()->count(),
            'totale_una_tantum' => $totaleUnaTantum,
            // Allineato con Dashboard
            'mrr_lordo' => $mrrLordo,
            'mrr_netto' => $mrrNetto,
            'utile' => $utile,
        ];

        return Inertia::render('Spese/Index', [
            'spese' => $spese,
            'stats' => $stats,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Spese/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'importo_totale' => 'required|numeric',
            'frequenza' => 'required|in:mensile,bimensile,trimestrale,annuale,una_tantum',
            'descrizione' => 'nullable|string',
            'categoria' => 'nullable|string',
            'data_prossimo_rinnovo' => 'nullable|date',
            'data_scadenza' => 'nullable|date',
            'metodo_pagamento' => 'nullable|in:carta,bonifico,paypal,stripe,altro',
            'note' => 'nullable|string',
        ]);

        // Calcola importo mensile in base alla frequenza
        $importoMensile = match($validated['frequenza']) {
            'mensile' => $validated['importo_totale'],
            'bimensile' => $validated['importo_totale'] / 2,
            'trimestrale' => $validated['importo_totale'] / 3,
            'annuale' => $validated['importo_totale'] / 12,
            'una_tantum' => 0,
        };

        $validated['importo_mensile'] = $importoMensile;

        Spesa::create($validated);

        return redirect()->route('spese.index')
            ->with('success', 'Spesa aggiunta con successo.');
    }

    public function edit(Spesa $spesa): Response
    {
        return Inertia::render('Spese/Edit', [
            'spesa' => $spesa,
        ]);
    }

    public function update(Request $request, Spesa $spesa): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'importo_totale' => 'required|numeric',
            'frequenza' => 'required|in:mensile,bimensile,trimestrale,annuale,una_tantum',
            'descrizione' => 'nullable|string',
            'categoria' => 'nullable|string',
            'data_prossimo_rinnovo' => 'nullable|date',
            'data_scadenza' => 'nullable|date',
            'metodo_pagamento' => 'nullable|in:carta,bonifico,paypal,stripe,altro',
            'note' => 'nullable|string',
            'attiva' => 'boolean',
        ]);

        // Calcola importo mensile
        $importoMensile = match($validated['frequenza']) {
            'mensile' => $validated['importo_totale'],
            'bimensile' => $validated['importo_totale'] / 2,
            'trimestrale' => $validated['importo_totale'] / 3,
            'annuale' => $validated['importo_totale'] / 12,
            'una_tantum' => 0,
        };

        $validated['importo_mensile'] = $importoMensile;

        $spesa->update($validated);

        return redirect()->route('spese.index')
            ->with('success', 'Spesa aggiornata con successo.');
    }

    public function destroy(Spesa $spesa): RedirectResponse
    {
        $spesa->delete();

        return redirect()->route('spese.index')
            ->with('success', 'Spesa eliminata con successo.');
    }

    public function batchDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:spesas,id'
        ]);

        $count = Spesa::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('spese.index')
            ->with('success', "{$count} spese eliminate con successo.");
    }
}
