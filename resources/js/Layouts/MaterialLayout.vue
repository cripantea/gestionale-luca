<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const drawer = ref(true);
const page = usePage();

const menuItems = [
    { title: 'Dashboard', icon: 'mdi-view-dashboard', route: 'dashboard' },
    { title: 'Contatti', icon: 'mdi-account-group', route: 'contacts.index' },
    { title: 'Progetti', icon: 'mdi-briefcase', route: 'projects.index' },
    { title: 'Task', icon: 'mdi-checkbox-marked-circle', route: 'tasks.index' },
];

const configItems = [
    { title: 'Tipi Progetto', icon: 'mdi-cog', route: 'project-types.index' },
    { title: 'Template Task', icon: 'mdi-file-document-multiple', route: 'task-templates.index' },
];
</script>

<template>
    <v-app>
        <!-- App Bar -->
        <v-app-bar color="primary" prominent>
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>Gestionale</v-toolbar-title>
            <v-spacer></v-spacer>

            <!-- User Menu -->
            <v-menu>
                <template v-slot:activator="{ props }">
                    <v-btn icon v-bind="props">
                        <v-icon>mdi-account-circle</v-icon>
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item>
                        <v-list-item-title>{{ page.props.auth.user.name }}</v-list-item-title>
                    </v-list-item>
                    <v-divider></v-divider>
                    <v-list-item :href="route('profile.edit')">
                        <template v-slot:prepend>
                            <v-icon>mdi-account</v-icon>
                        </template>
                        <v-list-item-title>Profilo</v-list-item-title>
                    </v-list-item>
                    <v-list-item :href="route('logout')" method="post" as="button">
                        <template v-slot:prepend>
                            <v-icon>mdi-logout</v-icon>
                        </template>
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>

        <!-- Navigation Drawer -->
        <v-navigation-drawer v-model="drawer" app>
            <v-list density="compact" nav>
                <v-list-item
                    v-for="item in menuItems"
                    :key="item.route"
                    :href="route(item.route)"
                    :active="route().current(item.route)"
                >
                    <template v-slot:prepend>
                        <v-icon>{{ item.icon }}</v-icon>
                    </template>
                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item>

                <v-divider class="my-2"></v-divider>

                <v-list-subheader>CONFIGURAZIONE</v-list-subheader>
                <v-list-item
                    v-for="item in configItems"
                    :key="item.route"
                    :href="route(item.route)"
                    :active="route().current(item.route)"
                >
                    <template v-slot:prepend>
                        <v-icon>{{ item.icon }}</v-icon>
                    </template>
                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <!-- Main Content -->
        <v-main>
            <!-- Flash Messages -->
            <v-container v-if="page.props.flash?.success" fluid>
                <v-alert
                    type="success"
                    closable
                    variant="tonal"
                >
                    {{ page.props.flash.success }}
                </v-alert>
            </v-container>

            <v-container v-if="page.props.flash?.error" fluid>
                <v-alert
                    type="error"
                    closable
                    variant="tonal"
                >
                    {{ page.props.flash.error }}
                </v-alert>
            </v-container>

            <!-- Page Content -->
            <v-container fluid>
                <slot />
            </v-container>
        </v-main>
    </v-app>
</template>

