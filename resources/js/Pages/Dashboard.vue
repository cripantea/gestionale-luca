<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    tasks: Array,
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
        'da_fare': 'bg-blue-100 text-blue-800',
        'in_corso': 'bg-yellow-100 text-yellow-800',
        'completato': 'bg-green-100 text-green-800',
        'in_pausa': 'bg-gray-100 text-gray-800',
        'in_attesa': 'bg-gray-100 text-gray-800',
    };
    return badges[status] || 'bg-gray-100 text-gray-800';
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

const overdueTasks = computed(() => props.tasks.filter(task => task.is_overdue));
const dueTodayTasks = computed(() => props.tasks.filter(task => task.is_due_today && !task.is_overdue));
const upcomingTasks = computed(() => props.tasks.filter(task => !task.is_overdue && !task.is_due_today));
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard - Task
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <!-- Statistics Cards -->
                <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="overflow-hidden bg-red-50 shadow-sm sm:rounded-lg dark:bg-red-900/20">
                        <div class="p-6">
                            <div class="text-sm font-medium text-red-600 dark:text-red-400">Scadute</div>
                            <div class="mt-2 text-3xl font-bold text-red-900 dark:text-red-100">{{ overdueTasks.length }}</div>
                        </div>
                    </div>
                    <div class="overflow-hidden bg-yellow-50 shadow-sm sm:rounded-lg dark:bg-yellow-900/20">
                        <div class="p-6">
                            <div class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Scadono Oggi</div>
                            <div class="mt-2 text-3xl font-bold text-yellow-900 dark:text-yellow-100">{{ dueTodayTasks.length }}</div>
                        </div>
                    </div>
                    <div class="overflow-hidden bg-blue-50 shadow-sm sm:rounded-lg dark:bg-blue-900/20">
                        <div class="p-6">
                            <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Prossime</div>
                            <div class="mt-2 text-3xl font-bold text-blue-900 dark:text-blue-100">{{ upcomingTasks.length }}</div>
                        </div>
                    </div>
                    <div class="overflow-hidden bg-indigo-50 shadow-sm sm:rounded-lg dark:bg-indigo-900/20">
                        <div class="p-6">
                            <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Totale</div>
                            <div class="mt-2 text-3xl font-bold text-indigo-900 dark:text-indigo-100">{{ tasks.length }}</div>
                        </div>
                    </div>
                </div>

                <!-- Overdue Tasks -->
                <div v-if="overdueTasks.length > 0" class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="bg-red-600 p-4 text-white">
                        <h3 class="text-lg font-semibold">⚠️ Task Scadute</h3>
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
                <div v-if="dueTodayTasks.length > 0" class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="bg-yellow-500 p-4 text-white">
                        <h3 class="text-lg font-semibold">📅 Task in Scadenza Oggi</h3>
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
                <div v-if="upcomingTasks.length > 0" class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="border-b border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">📋 Prossime Task</h3>
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
                <div v-if="tasks.length === 0" class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-12 text-center">
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

