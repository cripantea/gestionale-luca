<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spesa extends Model
{
    protected $fillable = [
        'nome',
        'importo_mensile',
        'importo_totale',
        'frequenza',
        'descrizione',
        'categoria',
        'data_prossimo_rinnovo',
        'data_scadenza',
        'metodo_pagamento',
        'note',
        'attiva',
    ];

    protected $casts = [
        'importo_mensile' => 'decimal:2',
        'importo_totale' => 'decimal:2',
        'data_prossimo_rinnovo' => 'date',
        'data_scadenza' => 'date',
        'attiva' => 'boolean',
    ];

    public function scopeAttive($query)
    {
        return $query->where('attiva', true);
    }

    public function scopeRicorrenti($query)
    {
        return $query->where('frequenza', '!=', 'una_tantum');
    }

    public function scopeUnaTantum($query)
    {
        return $query->where('frequenza', 'una_tantum');
    }

    public function getTotaleMensileAttribute()
    {
        return $this->importo_mensile;
    }

    public function getTotaleAnnualeAttribute()
    {
        return $this->importo_mensile * 12;
    }

    public function isUnaTantum(): bool
    {
        return $this->frequenza === 'una_tantum';
    }

    public function isRicorrente(): bool
    {
        return $this->frequenza !== 'una_tantum';
    }
}
