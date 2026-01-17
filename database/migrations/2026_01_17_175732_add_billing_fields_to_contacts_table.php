<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->enum('tipo_fatturazione', ['mensile', 'bimensile', 'trimestrale', 'annuale', 'una_tantum', 'commissionale'])->nullable()->after('accordo_economico_mensile');
            $table->enum('metodo_pagamento', ['bonifico', 'carta', 'contanti', 'paypal', 'stripe'])->nullable()->after('tipo_fatturazione');
            $table->date('data_prossima_fattura')->nullable()->after('metodo_pagamento');
            $table->boolean('ghl_attivo')->default(false)->after('data_prossima_fattura');
            $table->boolean('whatsapp_attivo')->default(false)->after('ghl_attivo');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['tipo_fatturazione', 'metodo_pagamento', 'data_prossima_fattura', 'ghl_attivo', 'whatsapp_attivo']);
        });
    }
};
