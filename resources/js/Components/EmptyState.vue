<script setup>
defineProps({
    icon: {
        type: String,
        default: '🗂️',
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: '',
    },
    actionText: {
        type: String,
        default: '',
    },
    actionHref: {
        type: String,
        default: '#',
    },
});
</script>

<template>
    <div class="rounded-lg bg-white px-6 py-12 text-center shadow-sm dark:bg-gray-800">
        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-gray-100 text-5xl dark:bg-gray-700">
            {{ icon }}
        </div>
        <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ title }}
        </h3>
        <p v-if="description" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            {{ description }}
        </p>
        <div v-if="actionText" class="mt-6">
            <Link
                :href="actionHref"
                class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all"
            >
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                {{ actionText }}
            </Link>
        </div>
        <slot></slot>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: { Link },
};
</script>
