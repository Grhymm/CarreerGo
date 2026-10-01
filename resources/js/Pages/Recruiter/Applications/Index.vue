<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import Badge from '@/Components/ui/Badge.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/ui/Icon.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import { formatRelative } from '@/utils/format';
import { recruiterApplicationStatuses } from '@/utils/status';
import { Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    applications: Array,
});

const columns = [
    { status: 'new', dot: 'bg-secondary-600' },
    { status: 'reviewing', dot: 'bg-primary-600' },
    { status: 'interview', dot: 'bg-tertiary-600' },
    { status: 'accepted', dot: 'bg-primary-800' },
    { status: 'rejected', dot: 'bg-red-700' },
];

const statusOptions = Object.entries(recruiterApplicationStatuses).map(([value, status]) => ({
    value,
    label: status.label,
}));

const applicationsByStatus = computed(() => (status) =>
    props.applications.filter((application) => application.status === status)
);

const activeCount = computed(
    () => props.applications.filter((application) => !['accepted', 'rejected'].includes(application.status)).length,
);

const changeStatus = (application, event) => {
    router.patch(
        route('recruiter.applications.update', application.id),
        { status: event.target.value },
        { preserveScroll: true }
    );
};
</script>

<template>
    <Head title="Candidatures reçues" />

    <AuthenticatedLayout>
        <PageHeader title="Candidatures reçues" description="Faites avancer chaque candidat en changeant son statut.">
            <template #actions>
                <p class="text-sm text-gray-600">
                    <span class="font-heading text-xl font-bold text-ink">{{ activeCount }}</span>
                    candidature{{ activeCount > 1 ? 's' : '' }} en cours
                </p>
            </template>
        </PageHeader>

        <EmptyState
            v-if="applications.length === 0"
            class="mt-8"
            icon="users"
            title="Aucune candidature reçue pour l’instant"
            description="Les candidatures à vos offres publiées apparaîtront ici."
        />

        <div v-else class="-mx-4 mt-8 overflow-x-auto px-4 pb-4 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="flex snap-x snap-mandatory gap-4">
                <section
                    v-for="column in columns"
                    :key="column.status"
                    class="w-72 shrink-0 snap-start rounded-2xl bg-sand/70 p-3"
                    :aria-labelledby="`colonne-${column.status}`"
                >
                    <h2 :id="`colonne-${column.status}`" class="mb-3 flex items-center justify-between px-1">
                        <span class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-ink">
                            <span class="h-2 w-2 rounded-full" :class="column.dot" aria-hidden="true"></span>
                            {{ recruiterApplicationStatuses[column.status].label }}
                        </span>
                        <span class="rounded-full bg-white px-2 py-0.5 text-xs font-semibold text-gray-700">
                            {{ applicationsByStatus(column.status).length }}
                        </span>
                    </h2>

                    <ul class="space-y-3">
                        <li
                            v-for="application in applicationsByStatus(column.status)"
                            :key="application.id"
                            class="rounded-xl border border-gray-200 bg-white p-4 shadow-card"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <Avatar :name="application.candidate_name" />
                                <Badge tone="tertiary">{{ application.compatibility_score }} % compatible</Badge>
                            </div>
                            <p class="mt-3 font-semibold text-ink">{{ application.candidate_name }}</p>
                            <p class="text-sm text-gray-600">{{ application.job_offer_title }}</p>

                            <div class="mt-3 flex items-center gap-1 border-t border-gray-100 pt-3 text-xs text-gray-600">
                                <Icon name="clock" size="xs" />
                                Reçue {{ formatRelative(application.applied_at) }}
                            </div>

                            <label :for="`statut-${application.id}`" class="sr-only">
                                Statut de la candidature de {{ application.candidate_name }}
                            </label>
                            <select
                                :id="`statut-${application.id}`"
                                class="ui-input mt-3"
                                :value="application.status"
                                @change="changeStatus(application, $event)"
                            >
                                <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                        </li>

                        <li v-if="applicationsByStatus(column.status).length === 0" class="px-1 py-4 text-center text-sm text-gray-600">
                            Aucune candidature.
                        </li>
                    </ul>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
