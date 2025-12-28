<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const showMobileMenu = ref(false);
const page = usePage();

const toggleMobileMenu = () => {
    showMobileMenu.value = !showMobileMenu.value;
};
</script>

<template>
    <div>
        <!-- Navbar -->
        <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
            <div class="container">
                <div class="navbar-brand">
                    <Link :href="route('dashboard')" class="navbar-item">
                        <strong>Gestionale</strong>
                    </Link>

                    <a
                        role="button"
                        class="navbar-burger"
                        :class="{ 'is-active': showMobileMenu }"
                        aria-label="menu"
                        aria-expanded="false"
                        @click="toggleMobileMenu"
                    >
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                    </a>
                </div>

                <div class="navbar-menu" :class="{ 'is-active': showMobileMenu }">
                    <div class="navbar-start">
                        <Link :href="route('dashboard')" class="navbar-item" :class="{ 'is-active': route().current('dashboard') }">
                            Dashboard
                        </Link>
                        <Link :href="route('contacts.index')" class="navbar-item" :class="{ 'is-active': route().current('contacts.*') }">
                            Contatti
                        </Link>
                        <Link :href="route('projects.index')" class="navbar-item" :class="{ 'is-active': route().current('projects.*') }">
                            Progetti
                        </Link>
                        <Link :href="route('tasks.index')" class="navbar-item" :class="{ 'is-active': route().current('tasks.*') }">
                            Task
                        </Link>

                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                Configurazione
                            </a>
                            <div class="navbar-dropdown">
                                <Link :href="route('project-types.index')" class="navbar-item">
                                    Tipi Progetto
                                </Link>
                                <Link :href="route('task-templates.index')" class="navbar-item">
                                    Template Task
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="navbar-end">
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                {{ page.props.auth.user.name }}
                            </a>
                            <div class="navbar-dropdown is-right">
                                <Link :href="route('profile.edit')" class="navbar-item">
                                    Profilo
                                </Link>
                                <hr class="navbar-divider">
                                <Link :href="route('logout')" method="post" as="button" class="navbar-item">
                                    Logout
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <div class="container mt-4" v-if="page.props.flash?.success">
            <div class="notification is-success is-light">
                <button class="delete" @click="page.props.flash.success = null"></button>
                {{ page.props.flash.success }}
            </div>
        </div>

        <div class="container mt-4" v-if="page.props.flash?.error">
            <div class="notification is-danger is-light">
                <button class="delete" @click="page.props.flash.error = null"></button>
                {{ page.props.flash.error }}
            </div>
        </div>

        <!-- Page Content -->
        <section class="section">
            <div class="container">
                <slot />
            </div>
        </section>
    </div>
</template>

<style scoped>
.navbar-item.is-active {
    background-color: rgba(255, 255, 255, 0.1);
}
</style>

