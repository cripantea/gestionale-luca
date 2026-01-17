<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->string('nome'); // es: "Gestione Social e Sito", "Gestionale Sinistri"
            $table->text('descrizione')->nullable();
            
            // Importi
            $table->decimal('importo_contratto', 10, 2); // es: 400 per mensile, 600 per annuale
            $table->enum('frequenza', ['mensile', 'bimensile', 'trimestrale', 'annuale', 'una_tantum', 'percentuale']);
            $table->decimal('mrr_calcolato', 10, 2); // MRR calcolato automaticamente
            
            // Fatturazione
            $table->date('data_prossima_fattura')->nullable();
            $table->date('data_ultima_fattura')->nullable();
            $table->enum('metodo_pagamento', ['bonifico', 'carta', 'contanti', 'cash', 'paypal', 'stripe', 'altro'])->nullable();
            
            // Percentuale (per deal tipo Studio Albani, Fairy Tooth)
            $table->decimal('percentuale', 5, 2)->nullable(); // es: 15.00 per 15%
            $table->string('condizioni_percentuale')->nullable(); // es: "sopra i 2 corsi venduti"
            
            // Status
            $table->boolean('attivo')->default(true);
            $table->date('data_inizio')->nullable();
            $table->date('data_fine')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
