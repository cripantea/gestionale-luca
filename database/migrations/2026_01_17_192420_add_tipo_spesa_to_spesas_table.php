<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spesas', function (Blueprint $table) {
            $table->date('data_scadenza')->nullable()->after('data_prossimo_rinnovo');
            $table->string('categoria')->nullable()->after('descrizione'); // Tasse, Università, Formazione, etc.
        });
    }

    public function down(): void
    {
        Schema::table('spesas', function (Blueprint $table) {
            $table->dropColumn(['data_scadenza', 'categoria']);
        });
    }
};
