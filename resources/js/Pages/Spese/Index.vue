<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Link, Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';

ChartJS.register(ArcElement, Tooltip, Legend);

const props = defineProps({
    spese: Array,
    stats: Object,
});

// Selezione multipla
const selectedSpese = ref([]);
const selectAll = ref(false);

// Dropdown e modali
const openDropdown = ref(null);
const showDeleteModal = ref(false);
const showBatchDeleteModal = ref(false);
const spesaToDelete = ref(null);

const formatCurrency = (value) => {
    if (!value && value !== 0) return '-';
    return new Intl.NumberFormat('it-IT', { style: 'currency', currency: 'EUR' }).format(Math.abs(value));
};

const getFrequenzaBadge = (frequenza) => {
    const badges = {
        'mensile': 'bg-blue-100 text-blue-800',
        'bimensile': 'bg-purple-100 text-purple-800',
        'trimestrale': 'bg-yellow-100 text-yellow-800',
        'annuale': 'bg-green-100 text-green-800',
        'una_tantum': 'bg-gray-100 text-gray-800',
    };
    return badges[frequenza] || 'bg-gray-100 text-gray-800';
};

const toggleDropdown = (spesaId) => {
    openDropdown.value = openDropdown.value === spesaId ? null : spesaId;
};

const confirmDelete = (spesa) => {
    spesaToDelete.value = spesa;
    showDeleteModal.value = true;
    openDropdown.value = null;
};

const deleteSpesa = () => {
    if (spesaToDelete.value) {
        router.delete(route('spese.destroy', spesaToDelete.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                spesaToDelete.value = null;
            }
        });
    }
};

// Selezione multipla
const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedSpese.value = props.spese.map(s => s.id);
    } else {
        selectedSpese.value = [];
    }
};

const toggleSelect = (spesaId) => {
    const index = selectedSpese.value.indexOf(spesaId);
    if (index > -1) {
        selectedSpese.value.splice(index, 1);
    } else {
        selectedSpese.value.push(spesaId);
    }
    selectAll.value = selectedSpese.value.length === props.spese.length;
};

const hasSelection = computed(() => selectedSpese.value.length > 0);

const confirmBatchDelete = () => {
    if (selectedSpese.value.length > 0) {
        showBatchDeleteModal.value = true;
    }
};

const batchDelete = () => {
    router.post(route('spese.batch-destroy'), {
        ids: selectedSpese.value
    }, {
        onSuccess: () => {
            selectedSpese.value = [];
            selectAll.value = false;
            showBatchDeleteModal.value = false;
        }
    });
};

// Grafico categorie spese (solo spese attive)
const speseAttive = computed(() => props.spese.filter(s => s.attiva));

const categorieData = computed(() => {
    const categories = {};
    const categoryLabels = {
        'tool': '🛠️ Tool & Software',
        'comunicazione': '📱 Comunicazione',
        'formazione': '📚 Formazione',
        'design': '🎨 Design & Asset',
        'fiscale': '💼 Fiscale & Legale',
        'tasse': '🔴 Tasse',
        'università': '🎓 Università',
        'altro': '📦 Altro',
    };

    speseAttive.value.forEach(spesa => {
        const cat = spesa.categoria || 'altro';
        if (!categories[cat]) {
            categories[cat] = 0;
        }
        // Somma importo mensile per spese ricorrenti, 0 per una tantum
        categories[cat] += spesa.frequenza !== 'una_tantum' ? parseFloat(spesa.importo_mensile) : 0;
    });

    const labels = Object.keys(categories).map(cat => categoryLabels[cat] || cat);
    const data = Object.values(categories);
    const total = data.reduce((sum, val) => sum + val, 0);
    const percentages = data.map(val => total > 0 ? ((val / total) * 100).toFixed(1) : 0);

    return {
        labels,
        datasets: [{
            data,
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',   // blue
                'rgba(147, 51, 234, 0.8)',   // purple
                'rgba(234, 179, 8, 0.8)',    // yellow
                'rgba(236, 72, 153, 0.8)',   // pink
                'rgba(249, 115, 22, 0.8)',   // orange
                'rgba(239, 68, 68, 0.8)',    // red
                'rgba(34, 197, 94, 0.8)',    // green
                'rgba(107, 114, 128, 0.8)',  // gray
            ],
            borderColor: [
                'rgba(59, 130, 246, 1)',
                'rgba(147, 51, 234, 1)',
                'rgba(234, 179, 8, 1)',
                'rgba(236, 72, 153, 1)',
                'rgba(249, 115, 22, 1)',
                'rgba(239, 68, 68, 1)',
                'rgba(34, 197, 94, 1)',
                'rgba(107, 114, 128, 1)',
            ],
            borderWidth: 2
        }],
        percentages
    };
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'right',
            labels: {
                font: {
                    size: 12
                },
                padding: 15,
                usePointStyle: true,
                generateLabels: (chart) => {
                    const data = chart.data;
                    if (data.labels.length && data.datasets.length) {
                        return data.labels.map((label, i) => {
                            const value = data.datasets[0].data[i];
                            const percentage = categorieData.value.percentages[i];
                            return {
                                text: `${label}: ${formatCurrency(value)} (${percentage}%)`,
                                fillStyle: data.datasets[0].backgroundColor[i],
                                strokeStyle: data.datasets[0].borderColor[i],
                                lineWidth: 2,
                                hidden: false,
                                index: i
                            };
                        });
                    }
                    return [];
                }
            }
        },
        tooltip: {
            callbacks: {
                label: (context) => {
                    const label = context.label || '';
                    const value = context.parsed || 0;
                    const percentage = categorieData.value.percentages[context.dataIndex];
                    return `${label}: ${formatCurrency(value)} (${percentage}%)`;
                }
            }
        }
    }
};
</script>

<template>
    <Head title="Spese" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    💸 Spese Aziendali
                </h2>
                <Link :href="route('spese.create')" class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 transition-all">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Nuova Spesa
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

                <!-- Grafico e Riepilogo -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Grafico Categorie -->
                    <div class="lg:col-span-2 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 animate-fadeIn">
                        <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                📊 Distribuzione Spese per Categoria
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Percentuale spese mensili attive per categoria
                            </p>
                        </div>
                        <div class="p-6">
                            <div v-if="speseAttive.length > 0" style="height: 300px;">
                                <Doughnut :data="categorieData" :options="chartOptions" />
                            </div>
                            <div v-else class="text-center py-12 text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2">Nessuna spesa attiva</p>
                            </div>
                        </div>
                    </div>

                    <!-- Widget Riepilogo -->
                    <div class="space-y-4">
                        <!-- Spese Mensili -->
                        <div class="overflow-hidden bg-gradient-to-br from-red-500 to-red-600 shadow-lg sm:rounded-lg animate-fadeIn hover:shadow-xl transition-all">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="w-full">
                                        <p class="text-sm font-medium text-red-100">💸 Spese Mensili</p>
                                        <p class="mt-2 text-3xl font-bold text-white">{{ formatCurrency(stats.totale_mensile) }}</p>
                                        <p class="mt-1 text-xs text-red-100">{{ stats.numero_spese }} spese attive</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <svg class="h-12 w-12 text-red-200 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Spese Annuali -->
                        <div class="overflow-hidden bg-gradient-to-br from-orange-500 to-orange-600 shadow-lg sm:rounded-lg animate-fadeIn hover:shadow-xl transition-all" style="animation-delay: 0.1s;">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="w-full">
                                        <p class="text-sm font-medium text-orange-100">📅 Spese Annuali</p>
                                        <p class="mt-2 text-3xl font-bold text-white">{{ formatCurrency(stats.totale_annuale) }}</p>
                                        <p class="mt-1 text-xs text-orange-100">Proiezione 12 mesi</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <svg class="h-12 w-12 text-orange-200 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Utile Netto -->
                        <div class="overflow-hidden bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-lg sm:rounded-lg animate-fadeIn hover:shadow-xl transition-all" style="animation-delay: 0.2s;">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="w-full">
                                        <p class="text-sm font-medium text-indigo-100">💎 Utile Netto</p>
                                        <p class="mt-2 text-3xl font-bold text-white">{{ formatCurrency(stats.utile) }}</p>
                                        <p class="mt-1 text-xs text-indigo-100">Dopo spese e tasse</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <svg class="h-12 w-12 text-indigo-200 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barra Azioni Batch -->
                <div v-if="hasSelection" class="overflow-hidden bg-indigo-50 border-2 border-indigo-200 shadow-sm sm:rounded-lg dark:bg-indigo-900/20 dark:border-indigo-700 animate-slideInRight">
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-medium text-indigo-900 dark:text-indigo-200">
                                {{ selectedSpese.length }} {{ selectedSpese.length === 1 ? 'spesa selezionata' : 'spese selezionate' }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button @click="confirmBatchDelete" 
                                    class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Elimina Selezionate
                            </button>
                            <button @click="selectedSpese = []; selectAll = false" 
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all">
                                Annulla
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabella Spese -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 animate-fadeIn" style="animation-delay: 0.3s;">
                    <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            📋 Dettaglio Spese
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left">
                                        <input type="checkbox" 
                                               v-model="selectAll" 
                                               @change="toggleSelectAll"
                                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Servizio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Categoria</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Costo Mensile</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Costo Totale</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Frequenza</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Data</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                <tr v-for="spesa in spese" :key="spesa.id" 
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" 
                                    :class="[
                                        !spesa.attiva ? 'opacity-50' : '',
                                        selectedSpese.includes(spesa.id) ? 'bg-indigo-50 dark:bg-indigo-900/20' : ''
                                    ]">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" 
                                               :checked="selectedSpese.includes(spesa.id)"
                                               @change="toggleSelect(spesa.id)"
                                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ spesa.nome }}</div>
                                        <div v-if="spesa.descrizione" class="text-xs text-gray-500 dark:text-gray-400">{{ spesa.descrizione }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span v-if="spesa.categoria" class="capitalize text-gray-700 dark:text-gray-300">
                                            {{ spesa.categoria }}
                                        </span>
                                        <span v-else class="text-gray-400">-</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold" :class="spesa.frequenza === 'una_tantum' ? 'text-gray-400' : 'text-red-600'">
                                        <span v-if="spesa.frequenza === 'una_tantum'">-</span>
                                        <span v-else>-{{ formatCurrency(spesa.importo_mensile) }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-300 font-semibold">
                                        -{{ formatCurrency(spesa.importo_totale) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold capitalize" :class="getFrequenzaBadge(spesa.frequenza)">
                                            {{ spesa.frequenza.replace('_', ' ') }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                        <span v-if="spesa.frequenza === 'una_tantum' && spesa.data_scadenza">
                                            <span class="text-red-600 font-medium">📅 {{ new Date(spesa.data_scadenza).toLocaleDateString('it-IT', { day: 'numeric', month: 'short', year: 'numeric' }) }}</span>
                                        </span>
                                        <span v-else-if="spesa.data_prossimo_rinnovo">
                                            <span class="text-blue-600">🔄 {{ new Date(spesa.data_prossimo_rinnovo).toLocaleDateString('it-IT', { day: 'numeric', month: 'short', year: 'numeric' }) }}</span>
                                        </span>
                                        <span v-else class="text-gray-400">-</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <span v-if="spesa.attiva" class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                                            Attiva
                                        </span>
                                        <span v-else class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800">
                                            Disattivata
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <div class="relative inline-block text-left">
                                            <button @click="toggleDropdown(spesa.id)" 
                                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                            
                                            <div v-if="openDropdown === spesa.id"
                                                 @click.away="openDropdown = null"
                                                 class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-gray-700 animate-fadeIn">
                                                <div class="py-1">
                                                    <Link :href="route('spese.edit', spesa.id)" 
                                                          class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                                                        <svg class="mr-3 h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Modifica
                                                    </Link>
                                                    <button @click="confirmDelete(spesa)"
                                                            class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                                                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Elimina
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <td></td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-100">TOTALE</td>
                                    <td></td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-red-600">
                                        -{{ formatCurrency(stats.totale_mensile) }}/mese
                                    </td>
                                    <td colspan="5"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- Delete Single Modal -->
        <ConfirmModal
            :show="showDeleteModal"
            @close="showDeleteModal = false"
            @confirm="deleteSpesa"
            title="Elimina Spesa"
            :message="`Sei sicuro di voler eliminare la spesa '${spesaToDelete?.nome}'? Questa azione non può essere annullata.`"
            confirmText="Elimina"
            confirmClass="bg-red-600 hover:bg-red-700"
        />

        <!-- Delete Batch Modal -->
        <ConfirmModal
            :show="showBatchDeleteModal"
            @close="showBatchDeleteModal = false"
            @confirm="batchDelete"
            title="Elimina Spese Selezionate"
            :message="`Sei sicuro di voler eliminare ${selectedSpese.length} ${selectedSpese.length === 1 ? 'spesa' : 'spese'}? Questa azione non può essere annullata.`"
            confirmText="Elimina Tutte"
            confirmClass="bg-red-600 hover:bg-red-700"
        />
    </AuthenticatedLayout>
</template>
