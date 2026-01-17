<script setup>
import { Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    tasks: Array,
});

// Funzioni di formattazione
const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('it-IT', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

const getStatusColor = (status) => {
    const colors = {
        'da_fare': 'border-blue-300 bg-blue-50 dark:bg-blue-900/20',
        'in_corso': 'border-yellow-300 bg-yellow-50 dark:bg-yellow-900/20',
        'completato': 'border-green-300 bg-green-50 dark:bg-green-900/20',
        'in_pausa': 'border-gray-300 bg-gray-50 dark:bg-gray-700/50',
        'in_attesa': 'border-purple-300 bg-purple-50 dark:bg-purple-900/20',
    };
    return colors[status] || 'border-gray-300 bg-gray-50';
};

// Raggruppa le task per stato
const tasksByStatus = computed(() => {
    return {
        da_fare: props.tasks.filter(t => t.status === 'da_fare'),
        in_corso: props.tasks.filter(t => t.status === 'in_corso'),
        in_pausa: props.tasks.filter(t => t.status === 'in_pausa'),
        in_attesa: props.tasks.filter(t => t.status === 'in_attesa'),
        completato: props.tasks.filter(t => t.status === 'completato'),
    };
});

const columns = [
    { key: 'da_fare', label: 'Da Fare', icon: '📋', color: 'bg-blue-500' },
    { key: 'in_corso', label: 'In Corso', icon: '🚀', color: 'bg-yellow-500' },
    { key: 'in_pausa', label: 'In Pausa', icon: '⏸️', color: 'bg-gray-500' },
    { key: 'in_attesa', label: 'In Attesa', icon: '⏳', color: 'bg-purple-500' },
    { key: 'completato', label: 'Completato', icon: '✅', color: 'bg-green-500' },
];

// Azioni rapide
const startTask = (taskId) => {
    router.post(route('tasks.start', taskId));
};

const completeTask = (taskId) => {
    router.post(route('tasks.complete', taskId));
};

const pauseTask = (taskId) => {
    router.post(route('tasks.pause', taskId));
};
</script>

<template>
    <div class="overflow-x-auto pb-4">
        <div class="inline-flex space-x-4 min-w-full px-4">
            <!-- Colonna per ogni stato -->
            <div
                v-for="column in columns"
                :key="column.key"
                class="flex-shrink-0 w-80"
            >
                <!-- Column Header -->
                <div :class="column.color" class="rounded-t-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-2xl">{{ column.icon }}</span>
                            <h3 class="font-semibold">{{ column.label }}</h3>
                        </div>
                        <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold bg-white/30 rounded-full">
                            {{ tasksByStatus[column.key].length }}
                        </span>
                    </div>
                </div>

                <!-- Column Content -->
                <div class="bg-gray-100 dark:bg-gray-800 rounded-b-lg p-4 min-h-[500px] max-h-[calc(100vh-300px)] overflow-y-auto space-y-3">
                    <!-- Task Card -->
                    <div
                        v-for="task in tasksByStatus[column.key]"
                        :key="task.id"
                        :class="getStatusColor(task.status)"
                        class="bg-white dark:bg-gray-700 rounded-lg border-l-4 shadow-sm hover:shadow-md transition-all cursor-pointer group"
                    >
                        <div class="p-4">
                            <!-- Task Title -->
                            <Link :href="route('tasks.show', task.id)" class="block">
                                <h4 class="font-medium text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ task.name }}
                                </h4>
                            </Link>

                            <!-- Project & Client -->
                            <div class="mt-2 space-y-1">
                                <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                    <svg class="mr-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="truncate">{{ task.project.name }}</span>
                                </div>
                                <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                    <svg class="mr-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="truncate">{{ task.project.contact_name }}</span>
                                </div>
                            </div>

                            <!-- Deadline -->
                            <div class="mt-3 flex items-center justify-between">
                                <div class="flex items-center text-xs"
                                     :class="task.is_overdue ? 'text-red-600 font-semibold' : task.is_due_today ? 'text-yellow-600 font-semibold' : 'text-gray-500 dark:text-gray-400'">
                                    <svg class="mr-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    {{ formatDate(task.deadline) }}
                                </div>
                                
                                <!-- Priority Badge -->
                                <span v-if="task.is_overdue" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200">
                                    Scaduta
                                </span>
                                <span v-else-if="task.is_due_today" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                                    Oggi
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="mt-3 flex items-center space-x-2 border-t border-gray-200 dark:border-gray-600 pt-3">
                                <button
                                    v-if="task.status === 'da_fare'"
                                    @click.stop="startTask(task.id)"
                                    class="flex-1 inline-flex items-center justify-center px-3 py-1.5 rounded-md bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition-colors"
                                >
                                    <svg class="mr-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                    </svg>
                                    Avvia
                                </button>
                                <button
                                    v-if="task.status === 'in_corso'"
                                    @click.stop="pauseTask(task.id)"
                                    class="flex-1 inline-flex items-center justify-center px-3 py-1.5 rounded-md bg-yellow-600 text-white text-xs font-medium hover:bg-yellow-700 transition-colors"
                                >
                                    <svg class="mr-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Pausa
                                </button>
                                <button
                                    v-if="task.status === 'in_corso'"
                                    @click.stop="completeTask(task.id)"
                                    class="flex-1 inline-flex items-center justify-center px-3 py-1.5 rounded-md bg-green-600 text-white text-xs font-medium hover:bg-green-700 transition-colors"
                                >
                                    <svg class="mr-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Completa
                                </button>
                                <Link
                                    :href="route('tasks.show', task.id)"
                                    class="flex-1 inline-flex items-center justify-center px-3 py-1.5 rounded-md bg-gray-200 text-gray-700 text-xs font-medium hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 transition-colors"
                                >
                                    <svg class="mr-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    Vedi
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Empty Column -->
                    <div v-if="tasksByStatus[column.key].length === 0" class="text-center py-8 text-gray-400 dark:text-gray-500">
                        <svg class="mx-auto h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-sm">Nessuna task</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
