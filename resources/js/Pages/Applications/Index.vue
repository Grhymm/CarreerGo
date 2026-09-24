<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    applications: Array,
});

const statusBadges = {
    new: { label: 'Nouvelle', classes: 'bg-tertiary-100 text-tertiary-800' },
    reviewing: { label: 'À l\'étude', classes: 'bg-secondary-100 text-secondary-800' },
    interview: { label: 'Entretien', classes: 'bg-primary-100 text-primary-800' },
    rejected: { label: 'Refusée', classes: 'bg-gray-100 text-gray-700' },
    accepted: { label: 'Acceptée', classes: 'bg-primary-600 text-white' },
};
</script>

<template>
    <Head title="Mes candidatures" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Mes candidatures
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Poste</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Entreprise</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Postulée le</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-if="applications.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Vous n'avez postulé à aucune offre pour l'instant.
                                </td>
                            </tr>
                            <tr v-for="application in applications" :key="application.id">
                                <td class="px-6 py-4">
                                    <Link
                                        :href="route('jobs.show', application.job_offer.id)"
                                        class="text-tertiary-600 hover:text-tertiary-800"
                                    >
                                        {{ application.job_offer.title }}
                                    </Link>
                                </td>
                                <td class="px-6 py-4">{{ application.job_offer.company.name }}</td>
                                <td class="px-6 py-4">{{ new Date(application.applied_at).toLocaleDateString('fr-FR') }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                        :class="statusBadges[application.status].classes"
                                    >
                                        {{ statusBadges[application.status].label }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
