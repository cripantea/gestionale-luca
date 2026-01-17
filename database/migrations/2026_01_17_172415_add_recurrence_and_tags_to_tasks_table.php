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
        Schema::table('tasks', function (Blueprint $table) {
            // Task ricorrenti
            $table->boolean('is_recurring')->default(false)->after('blocked_by_task_id');
            $table->enum('recurrence_pattern', ['daily', 'weekly', 'monthly', 'yearly'])->nullable()->after('is_recurring');
            $table->integer('recurrence_interval')->default(1)->after('recurrence_pattern'); // Ogni X giorni/settimane/mesi
            $table->date('recurrence_end_date')->nullable()->after('recurrence_interval');
            $table->foreignId('parent_recurring_task_id')->nullable()->constrained('tasks')->onDelete('cascade')->after('recurrence_end_date');
            
            // Sistema priorità intelligente
            $table->enum('priority', ['bassa', 'media', 'alta', 'critica'])->default('media')->after('parent_recurring_task_id');
            $table->integer('priority_score')->default(50)->after('priority'); // Calcolato automaticamente (0-100)
            $table->integer('estimated_hours')->nullable()->after('priority_score'); // Stima ore
            
            // Tag e categorie
            $table->json('tags')->nullable()->after('estimated_hours'); // ['urgente', 'bug', 'feature', 'design']
            $table->string('category')->nullable()->after('tags'); // 'sviluppo', 'design', 'seo', 'content', 'social'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['parent_recurring_task_id']);
            $table->dropColumn([
                'is_recurring',
                'recurrence_pattern',
                'recurrence_interval',
                'recurrence_end_date',
                'parent_recurring_task_id',
                'priority',
                'priority_score',
                'estimated_hours',
                'tags',
                'category',
            ]);
        });
    }
};
