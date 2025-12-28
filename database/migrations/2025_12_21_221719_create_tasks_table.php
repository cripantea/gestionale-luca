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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_template_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('blocked_by_task_id')->nullable()->constrained('tasks')->onDelete('set null'); // task che blocca questa
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['da_fare', 'in_corso', 'completato', 'in_pausa', 'in_attesa'])->default('da_fare');
            $table->timestamp('deadline')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->integer('total_work_time')->default(0); // in minuti
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
