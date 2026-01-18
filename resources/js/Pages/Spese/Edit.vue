<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Link, Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    spesa: Object,
});

// DEBUG: Log props per verificare cosa arriva dal controller
console.log('🔍 DEBUG Spesa/Edit.vue');
console.log('Props spesa:', props.spesa);
console.log('Nome:', props.spesa?.nome);
console.log('Importo:', props.spesa?.importo_totale);

const form = useForm({
    nome: props.spesa?.nome || '',
    importo_totale: props.spesa?.importo_totale || 0,
    frequenza: props.spesa?.frequenza || 'mensile',
    descrizione: props.spesa?.descrizione || '',
    categoria: props.spesa?.categoria || '',
    data_prossimo_rinnovo: props.spesa?.data_prossimo_rinnovo ? props.spesa.data_prossimo_rinnovo.split('T')[0] : '',
    data_scadenza: props.spesa?.data_scadenza ? props.spesa.data_scadenza.split('T')[0] : '',
    metodo_pagamento: props.spesa?.metodo_pagamento || '',
    note: props.spesa?.note || '',
    attiva: props.spesa?.attiva ?? true,
});

console.log('Form inizializzato:', form.data());

const showDeleteModal = ref(false);

const submit = () => {
    form.put(route('spese.update', props.spesa.id));
};

const deleteSpesa = () => {
    router.delete(route('spese.destroy', props.spesa.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
        }
    });
};
</script>

<template>
    <Head :title="`Modifica ${spesa.nome}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Modifica Spesa: {{ spesa.nome }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 animate-fadeIn">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome Servizio *</label>
                                <input v-model="form.nome" type="text" id="nome" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="importo_totale" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Importo (€) *</label>
                                    <input v-model="form.importo_totale" type="number" step="0.01" id="importo_totale" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>

                                <div>
                                    <label for="frequenza" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Frequenza *</label>
                                    <select v-model="form.frequenza" id="frequenza" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        <option value="mensile">Mensile</option>
                                        <option value="bimensile">Bimensile</option>
                                        <option value="trimestrale">Trimestrale</option>
                                        <option value="annuale">Annuale</option>
                                        <option value="una_tantum">Una Tantum</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label for="descrizione" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrizione</label>
                                    <input v-model="form.descrizione" type="text" id="descrizione"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>

                                <div>
                                    <label for="categoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                                    <select v-model="form.categoria" id="categoria"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        <option value="">- Seleziona -</option>
                                        <option value="tool">🛠️ Tool & Software</option>
                                        <option value="comunicazione">📱 Comunicazione</option>
                                        <option value="formazione">📚 Formazione</option>
                                        <option value="design">🎨 Design & Asset</option>
                                        <option value="fiscale">💼 Fiscale & Legale</option>
                                        <option value="tasse">🔴 Tasse</option>
                                        <option value="università">🎓 Università</option>
                                        <option value="altro">📦 Altro</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Data Scadenza (Una Tantum) o Rinnovo (Ricorrente) -->
                            <div v-if="form.frequenza === 'una_tantum'">
                                <label for="data_scadenza" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    📅 Data Scadenza *
                                </label>
                                <input v-model="form.data_scadenza" type="date" id="data_scadenza"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Quando devi pagare questa spesa</p>
                            </div>
                            
                            <div v-else>
                                <label for="data_prossimo_rinnovo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    🔄 Prossimo Rinnovo
                                </label>
                                <input v-model="form.data_prossimo_rinnovo" type="date" id="data_prossimo_rinnovo"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label for="metodo_pagamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Metodo Pagamento</label>
                                <select v-model="form.metodo_pagamento" id="metodo_pagamento"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">- Seleziona -</option>
                                    <option value="carta">Carta</option>
                                    <option value="bonifico">Bonifico</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="stripe">Stripe</option>
                                    <option value="altro">Altro</option>
                                </select>
                            </div>

                            <div>
                                <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Note</label>
                                <textarea v-model="form.note" id="note" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                            </div>

                            <div class="flex items-center">
                                <input v-model="form.attiva" type="checkbox" id="attiva"
                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="attiva" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    Spesa attiva
                                </label>
                            </div>

                            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                                <button 
                                    type="button"
                                    @click="showDeleteModal = true"
                                    class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all"
                                >
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Elimina Spesa
                                </button>

                                <div class="flex items-center space-x-3">
                                    <Link :href="route('spese.index')" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                        Annulla
                                    </Link>
                                    <button type="submit" :disabled="form.processing"
                                            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-all">
                                        Salva Modifiche
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <ConfirmModal
            :show="showDeleteModal"
            @close="showDeleteModal = false"
            @confirm="deleteSpesa"
            title="Elimina Spesa"
            :message="`Sei sicuro di voler eliminare la spesa '${spesa.nome}'? Questa azione non può essere annullata.`"
            confirmText="Elimina"
            confirmClass="bg-red-600 hover:bg-red-700"
        />
    </AuthenticatedLayout>
</template>
