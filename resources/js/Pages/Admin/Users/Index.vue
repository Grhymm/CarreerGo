<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, router, usePage } from '@inertiajs/vue3';

defineProps({
    users: Array,
});

const page = usePage();

const formatDate = (date) => new Date(date).toLocaleDateString('fr-FR');

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
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Gestion des utilisateurs
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    v-if="page.props.flash.error"
                    class="mb-4 rounded-md bg-red-100 px-4 py-3 text-sm text-red-700"
                >
                    {{ page.props.flash.error }}
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Rôle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Entreprise</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Inscrit le</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="user in users" :key="user.id">
                                <td class="px-6 py-4">{{ user.name }}</td>
                                <td class="px-6 py-4">{{ user.email }}</td>
                                <td class="px-6 py-4">{{ user.role }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                        :class="user.is_suspended ? 'bg-red-100 text-red-700' : 'bg-primary-100 text-primary-700'"
                                    >
                                        {{ user.is_suspended ? 'Suspendu' : 'Actif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div v-if="user.company" class="flex items-center gap-2">
                                        <span>{{ user.company.name }}</span>
                                        <span
                                            class="rounded-full px-2 py-0.5 text-xs font-medium"
                                            :class="user.company.is_verified ? 'bg-primary-100 text-primary-700' : 'bg-gray-100 text-gray-600'"
                                        >
                                            {{ user.company.is_verified ? 'Vérifiée' : 'Non vérifiée' }}
                                        </span>
                                        <SecondaryButton @click="toggleCompanyVerification(user.company)">
                                            {{ user.company.is_verified ? 'Retirer la vérification' : 'Vérifier' }}
                                        </SecondaryButton>
                                    </div>
                                    <span v-else class="text-gray-400">—</span>
                                </td>
                                <td class="px-6 py-4">{{ formatDate(user.created_at) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <DangerButton v-if="!user.is_suspended" @click="toggleSuspension(user)">
                                        Suspendre
                                    </DangerButton>
                                    <SecondaryButton v-else @click="toggleSuspension(user)">
                                        Réactiver
                                    </SecondaryButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
