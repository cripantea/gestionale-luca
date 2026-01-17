<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    tasks: Array,
    urgentTasks: Array,
    stats: Object,
    activeProjects: Array,
});

const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('it-IT', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const formatWorkTime = (minutes) => {
    if (!minutes) return '-';
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return `${hours}h ${mins}m`;
};

const getStatusBadge = (status) => {
    const badges = {
        'da_fare': 'bg-blue-100 text-blue-800 border-blue-200',
        'in_corso': 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'completato': 'bg-green-100 text-green-800 border-green-200',
        'in_pausa': 'bg-gray-100 text-gray-800 border-gray-200',
        'in_attesa': 'bg-purple-100 text-purple-800 border-purple-200',
    };
    return badges[status] || 'bg-gray-100 text-gray-800 border-gray-200';
};

const getStatusLabel = (status) => {
    const labels = {
        'da_fare': 'Da Fare',
        'in_corso': 'In Corso',
        'completato': 'Completato',
        'in_pausa': 'In Pausa',
        'in_attesa': 'In Attesa',
    };
    return labels[status] || status;
};

const startTask = (taskId) => {
    router.post(route('tasks.start', taskId));
};

const completeTask = (taskId) => {
    router.post(route('tasks.complete', taskId));
};

const overdueTasks = computed(() => props.tasks.filter(task => task.is_overdue));
const dueTodayTasks = computed(() => props.tasks.filter(task => task.is_due_today && !task.is_overdue));
const upcomingTasks = computed(() => props.tasks.filter(task => !task.is_overdue && !task.is_due_today));
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Dashboard
                </h2>
                <div class="flex space-x-3">
                    <Link :href="route('tasks.create')" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Nuova Task
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

                <!-- Overview Statistics Cards -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <!-- Contacts -->
                    <Link :href="route('contacts.index')" class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 hover:shadow-md transition-all transform hover:scale-105 cursor-pointer">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 rounded-md bg-blue-500 p-3">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Contatti</dt>
                                        <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ stats.total_contacts }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <!-- Clients -->
                    <Link :href="route('contacts.index')" class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 hover:shadow-md transition-all transform hover:scale-105 cursor-pointer">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 rounded-md bg-green-500 p-3">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Clienti</dt>
                                        <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ stats.total_clients }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <!-- Projects -->
                    <Link :href="route('projects.index')" class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 hover:shadow-md transition-all transform hover:scale-105 cursor-pointer">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 rounded-md bg-purple-500 p-3">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Progetti</dt>
                                        <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ stats.total_projects }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <!-- Active Projects -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 rounded-md bg-yellow-500 p-3">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Attivi</dt>
                                        <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ stats.active_projects }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Completed This Month -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 rounded-md bg-indigo-500 p-3">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Task Mese</dt>
                                        <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ stats.tasks_completed_this_month }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Urgent Tasks & Active Projects Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- Urgent Tasks with Quick Actions -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 bg-gradient-to-r from-red-50 to-orange-50 p-6 dark:border-gray-700 dark:from-red-900/20 dark:to-orange-900/20">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    ⚡ Task Urgenti
                                </h3>
                                <Link :href="route('tasks.index')" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                    Vedi tutte →
                                </Link>
                            </div>
                        </div>
                        <div class="p-6">
                            <div v-if="urgentTasks.length > 0" class="space-y-3">
                                <div v-for="task in urgentTasks" :key="task.id" 
                                     class="flex items-center justify-between p-4 rounded-lg border-l-4 bg-gray-50 dark:bg-gray-700/50 transition-all hover:shadow-md"
                                     :class="task.is_overdue ? 'border-red-500' : 'border-yellow-500'">
                                    <div class="flex-1 min-w-0">
                                        <Link :href="route('tasks.show', task.id)" class="text-sm font-medium text-gray-900 hover:text-indigo-600 dark:text-gray-100 dark:hover:text-indigo-400">
                                            {{ task.name }}
                                        </Link>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ task.project.contact_name }} · {{ formatDate(task.deadline) }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-4">
                                        <button
                                            v-if="task.status === 'da_fare'"
                                            @click="startTask(task.id)"
                                            class="inline-flex items-center p-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition-colors"
                                            title="Avvia Task"
                                        >
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <button
                                            v-if="task.status === 'in_corso'"
                                            @click="completeTask(task.id)"
                                            class="inline-flex items-center p-2 rounded-md bg-green-600 text-white hover:bg-green-700 transition-colors"
                                            title="Completa Task"
                                        >
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <Link :href="route('tasks.show', task.id)" class="inline-flex items-center p-2 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 transition-colors" title="Dettagli">
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Nessuna task urgente</p>
                            </div>
                        </div>
                    </div>

                    <!-- Active Projects Progress -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div class="border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 p-6 dark:border-gray-700 dark:from-blue-900/20 dark:to-indigo-900/20">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    📊 Progetti in Corso
                                </h3>
                                <Link :href="route('projects.index')" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                    Vedi tutti →
                                </Link>
                            </div>
                        </div>
                        <div class="p-6">
                            <div v-if="activeProjects.length > 0" class="space-y-4">
                                <div v-for="project in activeProjects" :key="project.id" class="border-l-4 border-indigo-500 pl-4 py-2 bg-gray-50 dark:bg-gray-700/50 rounded-r-lg hover:shadow-md transition-all">
                                    <div class="flex items-center justify-between mb-2">
                                        <Link :href="route('projects.show', project.id)" class="text-sm font-medium text-gray-900 hover:text-indigo-600 dark:text-gray-100 dark:hover:text-indigo-400">
                                            {{ project.name }}
                                        </Link>
                                        <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">{{ project.progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-600 mb-2">
                                        <div class="bg-gradient-to-r from-indigo-500 to-blue-600 h-2 rounded-full transition-all duration-500" :style="{ width: project.progress + '%' }"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span>{{ project.contact_name }}</span>
                                        <span>{{ project.completed_tasks_count }}/{{ project.tasks_count }} task</span>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Nessun progetto attivo</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task Status Cards -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="overflow-hidden bg-red-50 border-l-4 border-red-500 shadow-sm sm:rounded-lg dark:bg-red-900/20 dark:border-red-600 hover:shadow-md transition-all">
                        <div class="p-6">
                            <div class="text-sm font-medium text-red-600 dark:text-red-400">Task Scadute</div>
                            <div class="mt-2 text-3xl font-bold text-red-900 dark:text-red-100">{{ overdueTasks.length }}</div>
                        </div>
                    </div>
                    <div class="overflow-hidden bg-yellow-50 border-l-4 border-yellow-500 shadow-sm sm:rounded-lg dark:bg-yellow-900/20 dark:border-yellow-600 hover:shadow-md transition-all">
                        <div class="p-6">
                            <div class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Scadono Oggi</div>
                            <div class="mt-2 text-3xl font-bold text-yellow-900 dark:text-yellow-100">{{ dueTodayTasks.length }}</div>
                        </div>
                    </div>
                    <div class="overflow-hidden bg-blue-50 border-l-4 border-blue-500 shadow-sm sm:rounded-lg dark:bg-blue-900/20 dark:border-blue-600 hover:shadow-md transition-all">
                        <div class="p-6">
                            <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Prossime Task</div>
                            <div class="mt-2 text-3xl font-bold text-blue-900 dark:text-blue-100">{{ upcomingTasks.length }}</div>
                        </div>
                    </div>
                    <div class="overflow-hidden bg-indigo-50 border-l-4 border-indigo-500 shadow-sm sm:rounded-lg dark:bg-indigo-900/20 dark:border-indigo-600 hover:shadow-md transition-all">
                        <div class="p-6">
                            <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Task Totali</div>
                            <div class="mt-2 text-3xl font-bold text-indigo-900 dark:text-indigo-100">{{ tasks.length }}</div>
                        </div>
                    </div>
                </div>

                <!-- All Tasks Section -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="border-b border-gray-200 p-6 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            📋 Tutte le Task
                        </h3>
                    </div>

                    <!-- Overdue Tasks -->
                    <div v-if="overdueTasks.length > 0" class="border-b border-gray-200 dark:border-gray-700">
                        <div class="bg-gradient-to-r from-red-50 to-orange-50 p-4 dark:from-red-900/20 dark:to-orange-900/20">
                            <h4 class="text-md font-semibold text-red-800 dark:text-red-200">⚠️ Task Scadute ({{ overdueTasks.length }})</h4>
                        </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nome Task</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Progetto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Scadenza</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Stato</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Tempo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                <tr v-for="task in overdueTasks" :key="task.id" class="bg-red-50 dark:bg-red-900/10">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <Link :href="route('tasks.show', task.id)" class="font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            {{ task.name }}
                                        </Link>
                                    </td>
                                    <td class="px-6 py-4">
                                        <Link :href="route('projects.show', task.project.id)" class="text-sm text-gray-900 hover:text-gray-700 dark:text-gray-300">
                                            {{ task.project.name }}
                                        </Link>
                                        <div class="text-xs text-gray-500">{{ task.project.project_type_name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                        <Link :href="route('contacts.show', task.project.contact_id)" class="hover:text-gray-700">
                                            {{ task.project.contact_name }}
                                        </Link>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-red-600">{{ formatDate(task.deadline) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span :class="getStatusBadge(task.status)" class="inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                            {{ getStatusLabel(task.status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ formatWorkTime(task.total_work_time) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <Link :href="route('tasks.show', task.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            Vedi
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                    <!-- Due Today Tasks -->
                    <div v-if="dueTodayTasks.length > 0" class="border-b border-gray-200 dark:border-gray-700">
                        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-4 dark:from-yellow-900/20 dark:to-amber-900/20">
                            <h4 class="text-md font-semibold text-yellow-800 dark:text-yellow-200">📅 Task in Scadenza Oggi ({{ dueTodayTasks.length }})</h4>
                        </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nome Task</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Progetto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Scadenza</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Stato</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Tempo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                <tr v-for="task in dueTodayTasks" :key="task.id" class="bg-yellow-50 dark:bg-yellow-900/10">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <Link :href="route('tasks.show', task.id)" class="font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            {{ task.name }}
                                        </Link>
                                    </td>
                                    <td class="px-6 py-4">
                                        <Link :href="route('projects.show', task.project.id)" class="text-sm text-gray-900 hover:text-gray-700 dark:text-gray-300">
                                            {{ task.project.name }}
                                        </Link>
                                        <div class="text-xs text-gray-500">{{ task.project.project_type_name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                        <Link :href="route('contacts.show', task.project.contact_id)" class="hover:text-gray-700">
                                            {{ task.project.contact_name }}
                                        </Link>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ formatDate(task.deadline) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span :class="getStatusBadge(task.status)" class="inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                            {{ getStatusLabel(task.status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ formatWorkTime(task.total_work_time) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <Link :href="route('tasks.show', task.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            Vedi
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                    <!-- Upcoming Tasks -->
                    <div v-if="upcomingTasks.length > 0">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 dark:from-blue-900/20 dark:to-indigo-900/20">
                            <h4 class="text-md font-semibold text-blue-800 dark:text-blue-200">📋 Prossime Task ({{ upcomingTasks.length }})</h4>
                        </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nome Task</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Progetto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Scadenza</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Stato</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Tempo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                <tr v-for="task in upcomingTasks" :key="task.id">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <Link :href="route('tasks.show', task.id)" class="font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            {{ task.name }}
                                        </Link>
                                    </td>
                                    <td class="px-6 py-4">
                                        <Link :href="route('projects.show', task.project.id)" class="text-sm text-gray-900 hover:text-gray-700 dark:text-gray-300">
                                            {{ task.project.name }}
                                        </Link>
                                        <div class="text-xs text-gray-500">{{ task.project.project_type_name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                        <Link :href="route('contacts.show', task.project.contact_id)" class="hover:text-gray-700">
                                            {{ task.project.contact_name }}
                                        </Link>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ formatDate(task.deadline) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span :class="getStatusBadge(task.status)" class="inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                            {{ getStatusLabel(task.status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ formatWorkTime(task.total_work_time) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <Link :href="route('tasks.show', task.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                            Vedi
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                    <!-- Empty State -->
                    <div v-if="tasks.length === 0" class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nessuna task</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Inizia creando una nuova task.</p>
                        <div class="mt-6">
                            <Link :href="route('tasks.create')" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Crea Task
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

