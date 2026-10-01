<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import Badge from '@/Components/ui/Badge.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/ui/Icon.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import StatCard from '@/Components/ui/StatCard.vue';
import { formatDate } from '@/utils/format';
import { roleLabels } from '@/utils/status';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    users: Array,
});

const query = ref('');
const roleFilter = ref('');
const statusFilter = ref('');

const filteredUsers = computed(() => {
    const term = query.value.trim().toLowerCase();

    return props.users.filter((user) => {
        const matchesTerm = !term || user.name.toLowerCase().includes(term) || user.email.toLowerCase().includes(term);
        const matchesRole = !roleFilter.value || user.role === roleFilter.value;
        const matchesStatus = !statusFilter.value
            || (statusFilter.value === 'suspended' ? user.is_suspended : !user.is_suspended);

        return matchesTerm && matchesRole && matchesStatus;
    });
});

const stats = computed(() => {
    const now = new Date();
    const companies = new Map();

    props.users.forEach((user) => {
        if (user.company) {
            companies.set(user.company.id, user.company);
        }
    });

    return {
        newThisMonth: props.users.filter((user) => {
            const created = new Date(user.created_at);

            return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear();
        }).length,
        verifiedCompanies: [...companies.values()].filter((company) => company.is_verified).length,
        suspended: props.users.filter((user) => user.is_suspended).length,
    };
});

const toggleSuspension = (user) => {
    router.patch(route('admin.users.toggle-suspension', user.id), {}, { preserveScroll: true });
};

const toggleCompanyVerification = (company) => {
    router.patch(route('admin.companies.toggle-verification', company.id), {}, { preserveScroll: true });
};
</script>

<template>
    <Head title="Gestion des utilisateurs" />

    <AuthenticatedLayout>
        <PageHeader
            title="Gestion des utilisateurs"
            description="Gérez les comptes des candidats et des recruteurs, vérifiez les entreprises et appliquez les mesures de modération."
        />

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <StatCard tone="primary" icon="users" :value="stats.newThisMonth" label="Inscriptions ce mois-ci" />
            <StatCard tone="tertiary" icon="badgeCheck" :value="stats.verifiedCompanies" label="Entreprises vérifiées" />
            <StatCard icon="ban" :value="stats.suspended" label="Comptes suspendus" />
        </div>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <div class="relative flex-1">
                <label for="recherche-utilisateur" class="sr-only">Rechercher par nom ou e-mail</label>
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-600">
                    <Icon name="search" />
                </span>
                <input id="recherche-utilisateur" v-model="query" type="search" class="ui-input min-h-11 pl-10" placeholder="Rechercher par nom ou e-mail…" />
            </div>
            <div class="grid grid-cols-2 gap-3 sm:flex">
                <div>
                    <label for="filtre-role" class="sr-only">Filtrer par rôle</label>
                    <select id="filtre-role" v-model="roleFilter" class="ui-input min-h-11">
                        <option value="">Tous les rôles</option>
                        <option v-for="(role, value) in roleLabels" :key="value" :value="value">{{ role.label }}</option>
                    </select>
                </div>
                <div>
                    <label for="filtre-statut" class="sr-only">Filtrer par statut</label>
                    <select id="filtre-statut" v-model="statusFilter" class="ui-input min-h-11">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actifs</option>
                        <option value="suspended">Suspendus</option>
                    </select>
                </div>
            </div>
        </div>

        <p class="mt-4 text-sm text-gray-600" aria-live="polite">
            {{ filteredUsers.length }} utilisateur{{ filteredUsers.length > 1 ? 's' : '' }} sur {{ users.length }}
        </p>

        <EmptyState
            v-if="filteredUsers.length === 0"
            class="mt-4"
            icon="search"
            title="Aucun utilisateur ne correspond"
            description="Modifiez votre recherche ou vos filtres."
        />

        <ul v-else class="mt-4 space-y-3">
            <li
                v-for="user in filteredUsers"
                :key="user.id"
                class="rounded-2xl border border-gray-200 bg-white p-4 shadow-card sm:p-5"
            >
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center">
                    <div class="flex min-w-0 flex-1 items-center gap-3">
                        <Avatar :name="user.name" />
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-ink">{{ user.name }}</p>
                            <p class="truncate text-sm text-gray-600">{{ user.email }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 lg:w-80">
                        <Badge :tone="roleLabels[user.role]?.tone ?? 'neutral'">{{ roleLabels[user.role]?.label ?? user.role }}</Badge>
                        <Badge :tone="user.is_suspended ? 'danger' : 'success'" dot>
                            {{ user.is_suspended ? 'Suspendu' : 'Actif' }}
                        </Badge>
                        <Badge v-if="user.company" :tone="user.company.is_verified ? 'primary' : 'neutral'" :icon="user.company.is_verified ? 'badgeCheck' : 'building'">
                            {{ user.company.name }}{{ user.company.is_verified ? ' · vérifiée' : ' · non vérifiée' }}
                        </Badge>
                        <span class="text-xs text-gray-600">Inscrit le {{ formatDate(user.created_at) }}</span>
                    </div>

                    <div class="flex flex-wrap gap-2 lg:justify-end">
                        <button
                            v-if="user.company"
                            type="button"
                            class="inline-flex min-h-9 items-center gap-1.5 rounded-lg border px-3 text-sm font-semibold transition-colors"
                            :class="user.company.is_verified
                                ? 'border-primary-200 bg-primary-50 text-primary-700 hover:bg-primary-100'
                                : 'border-gray-300 bg-white text-ink hover:border-primary-600'"
                            @click="toggleCompanyVerification(user.company)"
                        >
                            <Icon name="badgeCheck" size="sm" />
                            {{ user.company.is_verified ? 'Retirer la vérification' : 'Vérifier l’entreprise' }}
                        </button>
                        <button
                            type="button"
                            class="inline-flex min-h-9 items-center gap-1.5 rounded-lg px-3 text-sm font-semibold transition-colors"
                            :class="user.is_suspended ? 'text-primary-700 hover:bg-primary-50' : 'text-red-700 hover:bg-red-50'"
                            @click="toggleSuspension(user)"
                        >
                            <Icon :name="user.is_suspended ? 'refresh' : 'ban'" size="sm" />
                            {{ user.is_suspended ? 'Réactiver' : 'Suspendre' }}
                        </button>
                    </div>
                </div>
            </li>
        </ul>
    </AuthenticatedLayout>
</template>
