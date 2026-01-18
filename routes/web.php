<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskTemplateController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Clients (Clienti)
    Route::resource('clients', ClientController::class);
    Route::get('/clients/{client}/export', [\App\Http\Controllers\ClientExportController::class, 'exportClient'])->name('clients.export');
    Route::get('/clients-export-all', [\App\Http\Controllers\ClientExportController::class, 'exportAll'])->name('clients.export.all');

    // Spese
    Route::resource('spese', \App\Http\Controllers\SpesaController::class)->parameters([
        'spese' => 'spesa'
    ]);

    // Subscriptions (Contratti/Abbonamenti)
    Route::resource('subscriptions', SubscriptionController::class);
    Route::post('/subscriptions/{subscription}/mark-paid', [SubscriptionController::class, 'markAsPaid'])->name('subscriptions.mark-paid');
    Route::post('/subscriptions/{subscription}/toggle-active', [SubscriptionController::class, 'toggleActive'])->name('subscriptions.toggle-active');

    // Project Types
    Route::resource('project-types', ProjectTypeController::class);

    // Task Templates
    Route::resource('task-templates', TaskTemplateController::class);

    // Projects
    Route::resource('projects', ProjectController::class);

    // Tasks
    Route::resource('tasks', TaskController::class);
    
    // Calendar
    Route::get('/calendar', [\App\Http\Controllers\CalendarController::class, 'index'])->name('calendar.index');

    // Task Actions
    Route::post('/tasks/{task}/start', [TaskController::class, 'start'])->name('tasks.start');
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('/tasks/{task}/pause', [TaskController::class, 'pause'])->name('tasks.pause');
    Route::post('/tasks/{task}/resume', [TaskController::class, 'resume'])->name('tasks.resume');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
