<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    jobOffers: Array,
});

const formatDate = (date) => new Date(date).toLocaleDateString('fr-FR');

const moderate = (jobOffer, status) => {
    router.patch(route('admin.moderation.update', jobOffer.id), { status });
};
</script>

<template>
    <Head title="Modération" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Modération des offres
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Titre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Entreprise</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Recruteur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Créée le</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-if="jobOffers.length === 0">
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Aucune offre en attente de modération.
                                </td>
                            </tr>
                            <tr v-for="jobOffer in jobOffers" :key="jobOffer.id">
                                <td class="px-6 py-4">{{ jobOffer.title }}</td>
                                <td class="px-6 py-4">{{ jobOffer.company.name }}</td>
                                <td class="px-6 py-4">{{ jobOffer.user.name }}</td>
                                <td class="px-6 py-4">{{ formatDate(jobOffer.created_at) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <PrimaryButton class="me-2" @click="moderate(jobOffer, 'published')">
                                        Valider
                                    </PrimaryButton>
                                    <DangerButton @click="moderate(jobOffer, 'rejected')">
                                        Rejeter
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
