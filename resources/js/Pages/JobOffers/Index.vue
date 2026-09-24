<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    jobOffers: Array,
});

const destroy = (jobOffer) => {
    if (confirm(`Supprimer l'offre "${jobOffer.title}" ?`)) {
        router.delete(route('job-offers.destroy', jobOffer.id));
    }
};
</script>

<template>
    <Head title="Mes offres" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Mes offres d'emploi
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-4 flex justify-end">
                    <Link :href="route('job-offers.create')">
                        <PrimaryButton>Créer une offre</PrimaryButton>
                    </Link>
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Titre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Localisation</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Contrat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Statut</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-if="jobOffers.length === 0">
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Aucune offre pour l'instant.
                                </td>
                            </tr>
                            <tr v-for="jobOffer in jobOffers" :key="jobOffer.id">
                                <td class="px-6 py-4">{{ jobOffer.title }}</td>
                                <td class="px-6 py-4">{{ jobOffer.location }}</td>
                                <td class="px-6 py-4">{{ jobOffer.contract_type }}</td>
                                <td class="px-6 py-4">{{ jobOffer.status }}</td>
                                <td class="px-6 py-4 text-right">
                                    <Link
                                        :href="route('job-offers.edit', jobOffer.id)"
                                        class="me-4 text-tertiary-600 hover:text-tertiary-800"
                                    >
                                        Modifier
                                    </Link>
                                    <DangerButton @click="destroy(jobOffer)">
                                        Supprimer
                                    </DangerButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
