<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import TaskCalendar from '@/Components/TaskCalendar.vue';

const props = defineProps({
    tasks: Array,
});
</script>

<template>
    <Head title="Calendario Task" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                📅 Calendario Task
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <TaskCalendar :tasks="tasks" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
