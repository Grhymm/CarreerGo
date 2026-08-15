<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    reports: Array,
});

const formatDate = (date) => new Date(date).toLocaleDateString('fr-FR');

const moderate = (report, status) => {
    router.patch(route('admin.reports.update', report.id), { status }, { preserveScroll: true });
};
</script>

<template>
    <Head title="Signalements" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Signalements
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Offre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Entreprise</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Motif</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Auteur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Date</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-if="reports.length === 0">
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Aucun signalement en attente.
                                </td>
                            </tr>
                            <tr v-for="report in reports" :key="report.id">
                                <td class="px-6 py-4">{{ report.job_offer.title }}</td>
                                <td class="px-6 py-4">{{ report.job_offer.company.name }}</td>
                                <td class="max-w-xs px-6 py-4">{{ report.reason }}</td>
                                <td class="px-6 py-4">{{ report.reporter.name }}</td>
                                <td class="px-6 py-4">{{ formatDate(report.created_at) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <PrimaryButton class="me-2" @click="moderate(report, 'reviewed')">
                                        Traiter
                                    </PrimaryButton>
                                    <DangerButton @click="moderate(report, 'dismissed')">
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
