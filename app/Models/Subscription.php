<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'contact_id',
        'nome',
        'descrizione',
        'importo_contratto',
        'frequenza',
        'mrr_calcolato',
        'data_prossima_fattura',
        'data_ultima_fattura',
        'metodo_pagamento',
        'percentuale',
        'condizioni_percentuale',
        'attivo',
        'data_inizio',
        'data_fine',
    ];

    protected $casts = [
        'importo_contratto' => 'decimal:2',
        'mrr_calcolato' => 'decimal:2',
        'percentuale' => 'decimal:2',
        'data_prossima_fattura' => 'date',
        'data_ultima_fattura' => 'date',
        'data_inizio' => 'date',
        'data_fine' => 'date',
        'attivo' => 'boolean',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Calcola il MRR in base alla frequenza
     */
    public function calcolaMrr(): float
    {
        if ($this->frequenza === 'percentuale' || $this->frequenza === 'una_tantum') {
            return 0;
        }

        return match($this->frequenza) {
            'mensile' => $this->importo_contratto,
            'bimensile' => $this->importo_contratto / 2,
            'trimestrale' => $this->importo_contratto / 3,
            'annuale' => $this->importo_contratto / 12,
            default => 0,
        };
    }

    /**
     * Aggiorna la prossima data di fattura dopo un pagamento
     */
    public function aggiornaDataFattura(): void
    {
        if (!$this->data_prossima_fattura || $this->frequenza === 'percentuale' || $this->frequenza === 'una_tantum') {
            return;
        }

        $this->data_ultima_fattura = $this->data_prossima_fattura;

        $this->data_prossima_fattura = match($this->frequenza) {
            'mensile' => $this->data_prossima_fattura->addMonth(),
            'bimensile' => $this->data_prossima_fattura->addMonths(2),
            'trimestrale' => $this->data_prossima_fattura->addMonths(3),
            'annuale' => $this->data_prossima_fattura->addYear(),
            default => $this->data_prossima_fattura,
        };

        $this->save();
    }

    /**
     * Scope per contratti attivi
     */
    public function scopeAttivi($query)
    {
        return $query->where('attivo', true);
    }

    /**
     * Scope per contratti con fattura in scadenza
     */
    public function scopeInScadenza($query, int $giorni = 7)
    {
        return $query->where('attivo', true)
            ->whereNotNull('data_prossima_fattura')
            ->whereBetween('data_prossima_fattura', [now(), now()->addDays($giorni)]);
    }

    /**
     * Scope per contratti scaduti
     */
    public function scopeScaduti($query)
    {
        return $query->where('attivo', true)
            ->whereNotNull('data_prossima_fattura')
            ->where('data_prossima_fattura', '<', now());
    }
}
