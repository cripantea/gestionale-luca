<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spesas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->decimal('importo_mensile', 10, 2); // Calcolato in base alla frequenza
            $table->decimal('importo_totale', 10, 2); // Importo effettivo (es. annuale)
            $table->enum('frequenza', ['mensile', 'bimensile', 'trimestrale', 'annuale', 'una_tantum']);
            $table->string('descrizione')->nullable(); // es. "15 ottobre", "9 domini", "6 account"
            $table->date('data_prossimo_rinnovo')->nullable();
            $table->enum('metodo_pagamento', ['carta', 'bonifico', 'paypal', 'stripe', 'altro'])->nullable();
            $table->text('note')->nullable();
            $table->boolean('attiva')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spesas');
    }
};
