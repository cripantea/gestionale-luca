<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'notes',
        'status',
        // Campi economici
        'accordo_economico_mensile',
        'tipo_fatturazione',
        'metodo_pagamento',
        'data_prossima_fattura',
        'ghl_attivo',
        'whatsapp_attivo',
        'dettagli_accordo',
        'potenziale_upsell',
        'note_upsell',
        // Informazioni business
        'esigenze_specifiche',
        'settore_business',
        'numero_dipendenti',
        'sito_web',
        'social_links',
        // Asset e brand
        'logo_path',
        'brand_colors',
        'font_preferences',
        // Contatti aggiuntivi
        'referente_principale',
        'telefono_secondario',
        'email_secondaria',
        // Tracking
        'data_acquisizione',
        'livello_soddisfazione',
        'nps_score',
        'canale_preferito',
        'timezone',
    ];

    protected $casts = [
        'status' => 'string',
        'accordo_economico_mensile' => 'decimal:2',
        'potenziale_upsell' => 'decimal:2',
        'numero_dipendenti' => 'integer',
        'nps_score' => 'integer',
        'social_links' => 'array',
        'brand_colors' => 'array',
        'font_preferences' => 'array',
        'data_acquisizione' => 'date',
        'data_prossima_fattura' => 'date',
        'ghl_attivo' => 'boolean',
        'whatsapp_attivo' => 'boolean',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function isCliente(): bool
    {
        return $this->status === 'cliente';
    }

    public function promoteToCliente(): void
    {
        $this->update(['status' => 'cliente']);
    }
}
