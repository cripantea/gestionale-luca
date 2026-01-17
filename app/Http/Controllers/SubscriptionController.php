<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function index(): Response
    {
        $subscriptions = Subscription::with('contact')
            ->orderBy('attivo', 'desc')
            ->orderBy('data_prossima_fattura', 'asc')
            ->get();

        return Inertia::render('Subscriptions/Index', [
            'subscriptions' => $subscriptions,
        ]);
    }

    public function create(Request $request): Response
    {
        $clients = Contact::orderBy('name')->get(['id', 'name', 'company']);
        $contactId = $request->query('contact_id');

        return Inertia::render('Subscriptions/Create', [
            'clients' => $clients,
            'preselectedContactId' => $contactId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'nome' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'importo_contratto' => 'required|numeric|min:0',
            'frequenza' => 'required|in:mensile,bimensile,trimestrale,annuale,una_tantum,percentuale',
            'data_prossima_fattura' => 'nullable|date',
            'metodo_pagamento' => 'nullable|in:bonifico,carta,contanti,cash,paypal,stripe,altro',
            'percentuale' => 'nullable|numeric|min:0|max:100',
            'condizioni_percentuale' => 'nullable|string',
            'data_inizio' => 'nullable|date',
        ]);

        // Calcola MRR
        $subscription = new Subscription($validated);
        $validated['mrr_calcolato'] = $subscription->calcolaMrr();
        $validated['attivo'] = true;

        $subscription = Subscription::create($validated);

        return redirect()->route('clients.show', $subscription->contact_id)
            ->with('success', 'Contratto creato con successo.');
    }

    public function edit(Subscription $subscription): Response
    {
        $subscription->load('contact');
        $clients = Contact::orderBy('name')->get(['id', 'name', 'company']);

        return Inertia::render('Subscriptions/Edit', [
            'subscription' => $subscription,
            'clients' => $clients,
        ]);
    }

    public function update(Request $request, Subscription $subscription): RedirectResponse
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'nome' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'importo_contratto' => 'required|numeric|min:0',
            'frequenza' => 'required|in:mensile,bimensile,trimestrale,annuale,una_tantum,percentuale',
            'data_prossima_fattura' => 'nullable|date',
            'data_ultima_fattura' => 'nullable|date',
            'metodo_pagamento' => 'nullable|in:bonifico,carta,contanti,cash,paypal,stripe,altro',
            'percentuale' => 'nullable|numeric|min:0|max:100',
            'condizioni_percentuale' => 'nullable|string',
            'attivo' => 'boolean',
            'data_inizio' => 'nullable|date',
            'data_fine' => 'nullable|date',
        ]);

        // Ricalcola MRR se importo o frequenza sono cambiati
        $tempSubscription = new Subscription($validated);
        $validated['mrr_calcolato'] = $tempSubscription->calcolaMrr();

        $subscription->update($validated);

        return redirect()->route('clients.show', $subscription->contact_id)
            ->with('success', 'Contratto aggiornato con successo.');
    }

    public function destroy(Subscription $subscription): RedirectResponse
    {
        $contactId = $subscription->contact_id;
        $subscription->delete();

        return redirect()->route('clients.show', $contactId)
            ->with('success', 'Contratto eliminato con successo.');
    }

    /**
     * Segna la fattura come pagata e aggiorna la prossima data
     */
    public function markAsPaid(Subscription $subscription): RedirectResponse
    {
        $subscription->aggiornaDataFattura();

        return back()->with('success', 'Fattura segnata come pagata. Prossima fattura aggiornata.');
    }

    /**
     * Attiva/Disattiva contratto
     */
    public function toggleActive(Subscription $subscription): RedirectResponse
    {
        $subscription->update([
            'attivo' => !$subscription->attivo,
        ]);

        $status = $subscription->attivo ? 'attivato' : 'disattivato';
        return back()->with('success', "Contratto {$status} con successo.");
    }
}
