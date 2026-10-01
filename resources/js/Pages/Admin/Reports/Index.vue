<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import Button from '@/Components/ui/Button.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/ui/Icon.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import StatCard from '@/Components/ui/StatCard.vue';
import { formatDate } from '@/utils/format';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    reports: Array,
});

const moderate = (report, status) => {
    router.patch(route('admin.reports.update', report.id), { status }, { preserveScroll: true });
};
</script>

<template>
    <Head title="Signalements" />

    <AuthenticatedLayout>
        <PageHeader
            title="Signalements"
            description="Examinez les offres signalées par les candidats, puis marquez chaque signalement comme traité ou rejeté."
        />

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <StatCard tone="accent" icon="flag" :value="reports.length" label="Signalements en attente" />
        </div>

        <EmptyState
            v-if="reports.length === 0"
            class="mt-8"
            icon="check"
            title="Aucun signalement en attente"
            description="Les signalements envoyés par les candidats apparaîtront ici."
        />

        <ul v-else class="mt-8 space-y-4">
            <li
                v-for="report in reports"
                :key="report.id"
                class="rounded-2xl border border-gray-200 bg-white p-4 shadow-card sm:p-6"
            >
                <div class="flex items-start gap-3">
                    <Avatar :name="report.job_offer.company.name" square />
                    <div class="min-w-0 flex-1">
                        <p class="font-heading text-base font-bold text-ink">{{ report.job_offer.title }}</p>
                        <p class="text-sm text-gray-600">{{ report.job_offer.company.name }}</p>
                    </div>
                </div>

                <blockquote class="mt-4 rounded-xl border-l-4 border-secondary-600 bg-sand px-4 py-3 text-sm text-ink">
                    {{ report.reason }}
                </blockquote>

                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="flex items-center gap-1.5 text-xs text-gray-600">
                        <Icon name="user" size="xs" />
                        Signalé par {{ report.reporter.name }} le {{ formatDate(report.created_at) }}
                    </p>
                    <div class="grid grid-cols-2 gap-2 sm:flex">
                        <Button size="sm" icon="check" @click="moderate(report, 'reviewed')">Marquer comme traité</Button>
                        <Button size="sm" variant="outlined" icon="x" @click="moderate(report, 'dismissed')">Rejeter</Button>
                    </div>
                </div>
            </li>
        </ul>
    </AuthenticatedLayout>
</template>
