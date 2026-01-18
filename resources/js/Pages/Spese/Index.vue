<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head } from '@inertiajs/vue3';

const props = defineProps({
    spese: Array,
    stats: Object,
});

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
</script>

<template>
    <Head title="Spese" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    💸 Spese Aziendali
                </h2>
                <Link :href="route('spese.create')" class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Nuova Spesa
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

                <!-- Cards Statistiche -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <!-- Spese Mensili -->
                    <div class="overflow-hidden bg-gradient-to-br from-red-500 to-red-600 shadow-lg sm:rounded-lg animate-fadeIn hover:shadow-xl transition-all transform hover:scale-105 cursor-pointer">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-red-100">💸 Spese Mensili</p>
                                    <p class="mt-2 text-3xl font-bold text-white">-{{ formatCurrency(stats.totale_mensile) }}</p>
                                    <p class="mt-1 text-xs text-red-100">{{ stats.numero_spese }} voci attive</p>
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
                    <div class="overflow-hidden bg-gradient-to-br from-orange-500 to-orange-600 shadow-lg sm:rounded-lg animate-fadeIn hover:shadow-xl transition-all transform hover:scale-105 cursor-pointer" style="animation-delay: 0.1s;">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-orange-100">📅 Spese Annuali</p>
                                    <p class="mt-2 text-3xl font-bold text-white">-{{ formatCurrency(stats.totale_annuale) }}</p>
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

                    <!-- Imponibile -->
                    <div class="overflow-hidden bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-lg sm:rounded-lg animate-fadeIn hover:shadow-xl transition-all transform hover:scale-105 cursor-pointer" style="animation-delay: 0.2s;">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-indigo-100">📊 Imponibile</p>
                                    <p class="mt-2 text-3xl font-bold text-white">{{ formatCurrency(stats.mrr - stats.totale_mensile) }}</p>
                                    <p class="mt-1 text-xs text-indigo-100">MRR {{ formatCurrency(stats.mrr) }} - Spese</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <svg class="h-12 w-12 text-indigo-200 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
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
                                <tr v-for="spesa in spese" :key="spesa.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="!spesa.attiva ? 'opacity-50' : ''">
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
                                        <Link :href="route('spese.edit', spesa.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 hover:underline transition-all">
                                            Modifica
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
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
    </AuthenticatedLayout>
</template>
