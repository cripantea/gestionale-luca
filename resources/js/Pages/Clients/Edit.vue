<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    client: {
        type: Object,
        required: true
    }
});

const activeTab = ref('base');

const form = useForm({
    // Info Base
    name: props.client.name || '',
    email: props.client.email || '',
    phone: props.client.phone || '',
    company: props.client.company || '',
    notes: props.client.notes || '',
    
    // Contatti Estesi
    referente_principale: props.client.referente_principale || '',
    telefono_secondario: props.client.telefono_secondario || '',
    email_secondaria: props.client.email_secondaria || '',
    canale_preferito: props.client.canale_preferito || 'email',
    
    // Business
    settore_business: props.client.settore_business || '',
    numero_dipendenti: props.client.numero_dipendenti || null,
    sito_web: props.client.sito_web || '',
    social_instagram: props.client.social_links?.instagram || '',
    social_facebook: props.client.social_links?.facebook || '',
    social_linkedin: props.client.social_links?.linkedin || '',
    esigenze_specifiche: props.client.esigenze_specifiche || '',
    
    // Economico
    accordo_economico_mensile: props.client.accordo_economico_mensile || null,
    dettagli_accordo: props.client.dettagli_accordo || '',
    potenziale_upsell: props.client.potenziale_upsell || null,
    note_upsell: props.client.note_upsell || '',
    
    // Tracking
    data_acquisizione: props.client.data_acquisizione ? props.client.data_acquisizione.split('T')[0] : new Date().toISOString().split('T')[0],
    livello_soddisfazione: props.client.livello_soddisfazione || null,
    nps_score: props.client.nps_score || null,
});

const submit = () => {
    // Trasforma social links in oggetto
    const socialLinks = {};
    if (form.social_instagram) socialLinks.instagram = form.social_instagram;
    if (form.social_facebook) socialLinks.facebook = form.social_facebook;
    if (form.social_linkedin) socialLinks.linkedin = form.social_linkedin;
    
    form.transform((data) => ({
        ...data,
        social_links: Object.keys(socialLinks).length > 0 ? socialLinks : null,
    })).put(route('clients.update', props.client.id));
};

const tabs = [
    { key: 'base', label: '📋 Info Base', icon: '📋' },
    { key: 'contatti', label: '📞 Contatti', icon: '📞' },
    { key: 'business', label: '🏢 Business', icon: '🏢' },
    { key: 'economico', label: '💰 Economico', icon: '💰' },
    { key: 'tracking', label: '📊 Tracking', icon: '📊' },
];
</script>

<template>
    <Head :title="`Modifica ${client.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Modifica Cliente: {{ client.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <!-- Tabs -->
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                            <button
                                v-for="tab in tabs"
                                :key="tab.key"
                                @click="activeTab = tab.key"
                                :class="[
                                    activeTab === tab.key
                                        ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
                                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400',
                                    'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors'
                                ]"
                            >
                                {{ tab.label }}
                            </button>
                        </nav>
                    </div>

                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Tab: Info Base -->
                            <div v-show="activeTab === 'base'" class="space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome *</label>
                                    <input v-model="form.name" type="text" id="name" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                           :class="{ 'border-red-500': form.errors.name }">
                                    <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
                                </div>

                                <div>
                                    <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Azienda</label>
                                    <input v-model="form.company" type="text" id="company"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                        <input v-model="form.email" type="email" id="email"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefono</label>
                                        <input v-model="form.phone" type="text" id="phone"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    </div>
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Note</label>
                                    <textarea v-model="form.notes" id="notes" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                                </div>
                            </div>

                            <!-- Tab: Contatti Estesi -->
                            <div v-show="activeTab === 'contatti'" class="space-y-6">
                                <div>
                                    <label for="referente_principale" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Referente Principale</label>
                                    <input v-model="form.referente_principale" type="text" id="referente_principale"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="email_secondaria" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Secondaria</label>
                                        <input v-model="form.email_secondaria" type="email" id="email_secondaria"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    </div>

                                    <div>
                                        <label for="telefono_secondario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefono Secondario</label>
                                        <input v-model="form.telefono_secondario" type="text" id="telefono_secondario"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    </div>
                                </div>

                                <div>
                                    <label for="canale_preferito" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Canale Preferito</label>
                                    <select v-model="form.canale_preferito" id="canale_preferito"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        <option value="email">📧 Email</option>
                                        <option value="telefono">📞 Telefono</option>
                                        <option value="whatsapp">💬 WhatsApp</option>
                                        <option value="meet">🎥 Meet/Video</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tab: Business -->
                            <div v-show="activeTab === 'business'" class="space-y-6">
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="settore_business" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Settore Business</label>
                                        <input v-model="form.settore_business" type="text" id="settore_business"
                                               placeholder="es. E-commerce, Wellness, B2B..."
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    </div>

                                    <div>
                                        <label for="numero_dipendenti" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Numero Dipendenti</label>
                                        <input v-model="form.numero_dipendenti" type="number" id="numero_dipendenti"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    </div>
                                </div>

                                <div>
                                    <label for="sito_web" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sito Web</label>
                                    <input v-model="form.sito_web" type="url" id="sito_web"
                                           placeholder="https://..."
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Social Media</label>
                                    <div class="space-y-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-2xl">📷</span>
                                            <input v-model="form.social_instagram" type="url" placeholder="Instagram URL"
                                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-2xl">👍</span>
                                            <input v-model="form.social_facebook" type="url" placeholder="Facebook URL"
                                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-2xl">💼</span>
                                            <input v-model="form.social_linkedin" type="url" placeholder="LinkedIn URL"
                                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="esigenze_specifiche" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Esigenze Specifiche</label>
                                    <textarea v-model="form.esigenze_specifiche" id="esigenze_specifiche" rows="4"
                                              placeholder="Note su preferenze, esigenze particolari, obiettivi..."
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                                </div>
                            </div>

                            <!-- Tab: Economico -->
                            <div v-show="activeTab === 'economico'" class="space-y-6">
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="accordo_economico_mensile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Accordo Mensile (€)</label>
                                        <input v-model="form.accordo_economico_mensile" type="number" step="0.01" id="accordo_economico_mensile"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    </div>

                                    <div>
                                        <label for="potenziale_upsell" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Potenziale Upsell (€)</label>
                                        <input v-model="form.potenziale_upsell" type="number" step="0.01" id="potenziale_upsell"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    </div>
                                </div>

                                <div>
                                    <label for="dettagli_accordo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dettagli Accordo</label>
                                    <textarea v-model="form.dettagli_accordo" id="dettagli_accordo" rows="3"
                                              placeholder="es. Gestione sito + SEO mensile + 2 campagne social..."
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                                </div>

                                <div>
                                    <label for="note_upsell" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Note Upsell</label>
                                    <textarea v-model="form.note_upsell" id="note_upsell" rows="3"
                                              placeholder="Opportunità di espansione, servizi aggiuntivi interessati..."
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"></textarea>
                                </div>
                            </div>

                            <!-- Tab: Tracking -->
                            <div v-show="activeTab === 'tracking'" class="space-y-6">
                                <div>
                                    <label for="data_acquisizione" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data Acquisizione</label>
                                    <input v-model="form.data_acquisizione" type="date" id="data_acquisizione"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                </div>

                                <div>
                                    <label for="livello_soddisfazione" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Livello Soddisfazione</label>
                                    <select v-model="form.livello_soddisfazione" id="livello_soddisfazione"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                        <option :value="null">- Seleziona -</option>
                                        <option value="molto_insoddisfatto">😞 Molto Insoddisfatto</option>
                                        <option value="insoddisfatto">😕 Insoddisfatto</option>
                                        <option value="neutrale">😐 Neutrale</option>
                                        <option value="soddisfatto">😊 Soddisfatto</option>
                                        <option value="molto_soddisfatto">🤩 Molto Soddisfatto</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="nps_score" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NPS Score (0-10)</label>
                                    <input v-model="form.nps_score" type="number" min="0" max="10" id="nps_score"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <p class="mt-1 text-sm text-gray-500">0-6: Detrattori | 7-8: Passivi | 9-10: Promotori</p>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <Link :href="route('clients.show', client.id)" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    Annulla
                                </Link>
                                <button type="submit" :disabled="form.processing"
                                        class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 disabled:opacity-50">
                                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Salva Modifiche
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
