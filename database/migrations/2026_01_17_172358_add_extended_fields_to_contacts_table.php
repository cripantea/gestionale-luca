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
        Schema::table('contacts', function (Blueprint $table) {
            // Informazioni economiche
            $table->decimal('accordo_economico_mensile', 10, 2)->nullable()->after('notes');
            $table->text('dettagli_accordo')->nullable()->after('accordo_economico_mensile');
            
            // Potenziale upsell
            $table->decimal('potenziale_upsell', 10, 2)->nullable()->after('dettagli_accordo');
            $table->text('note_upsell')->nullable()->after('potenziale_upsell');
            
            // Note varie e informazioni aggiuntive
            $table->text('esigenze_specifiche')->nullable()->after('note_upsell');
            $table->string('settore_business')->nullable()->after('esigenze_specifiche');
            $table->integer('numero_dipendenti')->nullable()->after('settore_business');
            $table->string('sito_web')->nullable()->after('numero_dipendenti');
            $table->json('social_links')->nullable()->after('sito_web'); // Instagram, Facebook, LinkedIn
            
            // Asset e documenti
            $table->string('logo_path')->nullable()->after('social_links');
            $table->json('brand_colors')->nullable()->after('logo_path'); // Colori brand
            $table->json('font_preferences')->nullable()->after('brand_colors'); // Font preferiti
            
            // Informazioni contatto aggiuntive
            $table->string('referente_principale')->nullable()->after('font_preferences');
            $table->string('telefono_secondario')->nullable()->after('referente_principale');
            $table->string('email_secondaria')->nullable()->after('telefono_secondario');
            
            // Tracking e performance
            $table->date('data_acquisizione')->nullable()->after('email_secondaria');
            $table->enum('livello_soddisfazione', ['molto_insoddisfatto', 'insoddisfatto', 'neutrale', 'soddisfatto', 'molto_soddisfatto'])->nullable()->after('data_acquisizione');
            $table->integer('nps_score')->nullable()->after('livello_soddisfazione'); // Net Promoter Score (0-10)
            
            // Preferenze comunicazione
            $table->enum('canale_preferito', ['email', 'telefono', 'whatsapp', 'meet'])->default('email')->after('nps_score');
            $table->string('timezone')->default('Europe/Rome')->after('canale_preferito');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn([
                'accordo_economico_mensile',
                'dettagli_accordo',
                'potenziale_upsell',
                'note_upsell',
                'esigenze_specifiche',
                'settore_business',
                'numero_dipendenti',
                'sito_web',
                'social_links',
                'logo_path',
                'brand_colors',
                'font_preferences',
                'referente_principale',
                'telefono_secondario',
                'email_secondaria',
                'data_acquisizione',
                'livello_soddisfazione',
                'nps_score',
                'canale_preferito',
                'timezone',
            ]);
        });
    }
};
