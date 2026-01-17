<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    tasks: Array,
});

const currentDate = ref(new Date());

const currentMonth = computed(() => currentDate.value.getMonth());
const currentYear = computed(() => currentDate.value.getFullYear());

const monthNames = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 
                    'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];

const daysInMonth = computed(() => {
    return new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
});

const firstDayOfMonth = computed(() => {
    return new Date(currentYear.value, currentMonth.value, 1).getDay();
});

const calendarDays = computed(() => {
    const days = [];
    const startDay = firstDayOfMonth.value === 0 ? 6 : firstDayOfMonth.value - 1; // Lunedì = 0
    
    // Giorni vuoti all'inizio
    for (let i = 0; i < startDay; i++) {
        days.push({ day: null, tasks: [] });
    }
    
    // Giorni del mese
    for (let day = 1; day <= daysInMonth.value; day++) {
        const date = new Date(currentYear.value, currentMonth.value, day);
        const tasksForDay = props.tasks.filter(task => {
            if (!task.deadline) return false;
            const taskDate = new Date(task.deadline);
            return taskDate.getDate() === day && 
                   taskDate.getMonth() === currentMonth.value && 
                   taskDate.getFullYear() === currentYear.value;
        });
        days.push({ day, tasks: tasksForDay, isToday: isToday(date) });
    }
    
    return days;
});

const isToday = (date) => {
    const today = new Date();
    return date.getDate() === today.getDate() && 
           date.getMonth() === today.getMonth() && 
           date.getFullYear() === today.getFullYear();
};

const previousMonth = () => {
    currentDate.value = new Date(currentYear.value, currentMonth.value - 1, 1);
};

const nextMonth = () => {
    currentDate.value = new Date(currentYear.value, currentMonth.value + 1, 1);
};

const goToToday = () => {
    currentDate.value = new Date();
};

const getStatusColor = (status) => {
    const colors = {
        'da_fare': 'bg-blue-100 text-blue-800 border-blue-300',
        'in_corso': 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'completato': 'bg-green-100 text-green-800 border-green-300',
        'in_pausa': 'bg-gray-100 text-gray-800 border-gray-300',
        'in_attesa': 'bg-purple-100 text-purple-800 border-purple-300',
    };
    return colors[status] || 'bg-gray-100 text-gray-800 border-gray-300';
};

const getPriorityIcon = (priority) => {
    const icons = {
        'critica': '🔴',
        'alta': '🟠',
        'media': '🟡',
        'bassa': '🟢',
    };
    return icons[priority] || '';
};
</script>

<template>
    <div class="bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
        <!-- Header -->
        <div class="border-b border-gray-200 p-4 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ monthNames[currentMonth] }} {{ currentYear }}
                </h2>
                <div class="flex space-x-2">
                    <button @click="previousMonth" class="rounded-md border border-gray-300 bg-white px-3 py-1 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        ← Prev
                    </button>
                    <button @click="goToToday" class="rounded-md border border-indigo-300 bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700 hover:bg-indigo-100">
                        Oggi
                    </button>
                    <button @click="nextMonth" class="rounded-md border border-gray-300 bg-white px-3 py-1 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        Next →
                    </button>
                </div>
            </div>
        </div>

        <!-- Calendario -->
        <div class="p-4">
            <!-- Giorni della settimana -->
            <div class="grid grid-cols-7 gap-1 mb-2">
                <div v-for="day in ['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom']" :key="day" class="text-center text-xs font-semibold text-gray-600 dark:text-gray-400 py-2">
                    {{ day }}
                </div>
            </div>

            <!-- Giorni del mese -->
            <div class="grid grid-cols-7 gap-1">
                <div v-for="(dayData, index) in calendarDays" :key="index" class="min-h-[100px] border border-gray-200 dark:border-gray-700 rounded-lg p-2"
                     :class="{ 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-300': dayData.isToday, 'bg-gray-50/50 dark:bg-gray-900/50': !dayData.day }">
                    
                    <div v-if="dayData.day" class="h-full flex flex-col">
                        <!-- Numero del giorno -->
                        <div class="text-sm font-medium mb-1" :class="dayData.isToday ? 'text-indigo-700 dark:text-indigo-300 font-bold' : 'text-gray-700 dark:text-gray-300'">
                            {{ dayData.day }}
                        </div>

                        <!-- Tasks del giorno -->
                        <div class="space-y-1 flex-1 overflow-y-auto">
                            <Link v-for="task in dayData.tasks.slice(0, 3)" :key="task.id" :href="route('tasks.show', task.id)" 
                                  :class="['block text-xs px-2 py-1 rounded border truncate hover:shadow-sm transition-shadow', getStatusColor(task.status)]"
                                  :title="task.name">
                                <span v-if="task.priority" class="mr-1">{{ getPriorityIcon(task.priority) }}</span>
                                {{ task.name }}
                            </Link>
                            <div v-if="dayData.tasks.length > 3" class="text-xs text-gray-500 dark:text-gray-400 px-2">
                                +{{ dayData.tasks.length - 3 }} altre
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legenda -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-4">
            <div class="flex flex-wrap gap-3 text-xs">
                <div class="flex items-center space-x-1">
                    <div class="w-3 h-3 bg-blue-100 border border-blue-300 rounded"></div>
                    <span class="text-gray-600 dark:text-gray-400">Da Fare</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-3 h-3 bg-yellow-100 border border-yellow-300 rounded"></div>
                    <span class="text-gray-600 dark:text-gray-400">In Corso</span>
                </div>
                <div class="flex items-center space-x-1">
                    <div class="w-3 h-3 bg-green-100 border border-green-300 rounded"></div>
                    <span class="text-gray-600 dark:text-gray-400">Completato</span>
                </div>
                <div class="flex items-center space-x-1">
                    <span class="text-gray-600 dark:text-gray-400">Priorità:</span>
                    <span>🔴 Critica</span>
                    <span>🟠 Alta</span>
                    <span>🟡 Media</span>
                    <span>🟢 Bassa</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom scrollbar per i giorni con molte task */
div::-webkit-scrollbar {
    width: 4px;
}

div::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.5);
    border-radius: 2px;
}
</style>
