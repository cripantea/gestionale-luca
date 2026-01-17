<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    public function index(): Response
    {
        $clients = Contact::withCount('projects')
            ->latest()
            ->get();

        return Inertia::render('Clients/Index', [
            'clients' => $clients,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Clients/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Base
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            // Contatti
            'referente_principale' => 'nullable|string|max:255',
            'telefono_secondario' => 'nullable|string|max:255',
            'email_secondaria' => 'nullable|email|max:255',
            'canale_preferito' => 'nullable|in:email,telefono,whatsapp,meet',
            // Business
            'settore_business' => 'nullable|string|max:255',
            'numero_dipendenti' => 'nullable|integer',
            'sito_web' => 'nullable|url|max:255',
            'social_links' => 'nullable|array',
            'esigenze_specifiche' => 'nullable|string',
            // Economico
            'accordo_economico_mensile' => 'nullable|numeric|min:0',
            'dettagli_accordo' => 'nullable|string',
            'potenziale_upsell' => 'nullable|numeric|min:0',
            'note_upsell' => 'nullable|string',
            // Tracking
            'data_acquisizione' => 'nullable|date',
            'livello_soddisfazione' => 'nullable|in:molto_insoddisfatto,insoddisfatto,neutrale,soddisfatto,molto_soddisfatto',
            'nps_score' => 'nullable|integer|min:0|max:10',
        ]);

        // Tutti sono clienti - imposta automaticamente status = 'cliente'
        $validated['status'] = 'cliente';

        $client = Contact::create($validated);

        return redirect()->route('clients.show', $client->id)
            ->with('success', 'Cliente creato con successo.');
    }

    public function show(Contact $client): Response
    {
        $client->load(['projects.projectType', 'projects.tasks']);

        return Inertia::render('Clients/Show', [
            'client' => $client,
        ]);
    }

    public function edit(Contact $client): Response
    {
        return Inertia::render('Clients/Edit', [
            'client' => $client,
        ]);
    }

    public function update(Request $request, Contact $client): RedirectResponse
    {
        $validated = $request->validate([
            // Base
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            // Contatti
            'referente_principale' => 'nullable|string|max:255',
            'telefono_secondario' => 'nullable|string|max:255',
            'email_secondaria' => 'nullable|email|max:255',
            'canale_preferito' => 'nullable|in:email,telefono,whatsapp,meet',
            // Business
            'settore_business' => 'nullable|string|max:255',
            'numero_dipendenti' => 'nullable|integer',
            'sito_web' => 'nullable|url|max:255',
            'social_links' => 'nullable|array',
            'esigenze_specifiche' => 'nullable|string',
            // Economico
            'accordo_economico_mensile' => 'nullable|numeric|min:0',
            'dettagli_accordo' => 'nullable|string',
            'potenziale_upsell' => 'nullable|numeric|min:0',
            'note_upsell' => 'nullable|string',
            // Tracking
            'data_acquisizione' => 'nullable|date',
            'livello_soddisfazione' => 'nullable|in:molto_insoddisfatto,insoddisfatto,neutrale,soddisfatto,molto_soddisfatto',
            'nps_score' => 'nullable|integer|min:0|max:10',
        ]);

        $client->update($validated);

        return redirect()->route('clients.show', $client->id)
            ->with('success', 'Cliente aggiornato con successo.');
    }

    public function destroy(Contact $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Cliente eliminato con successo.');
    }
}
