<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import Badge from '@/Components/ui/Badge.vue';
import Button from '@/Components/ui/Button.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import StatCard from '@/Components/ui/StatCard.vue';
import Tabs from '@/Components/ui/Tabs.vue';
import { formatDate } from '@/utils/format';
import { applicationStatuses } from '@/utils/status';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    applications: Array,
});

const finished = ['rejected', 'accepted'];

const groups = computed(() => ({
    all: props.applications,
    ongoing: props.applications.filter((application) => !finished.includes(application.status)),
    finished: props.applications.filter((application) => finished.includes(application.status)),
}));

const activeTab = ref('all');

const tabs = computed(() => [
    { value: 'all', label: 'Toutes', count: groups.value.all.length },
    { value: 'ongoing', label: 'En cours', count: groups.value.ongoing.length },
    { value: 'finished', label: 'Terminées', count: groups.value.finished.length },
]);

const interviewCount = computed(
    () => props.applications.filter((application) => application.status === 'interview').length,
);
</script>

<template>
    <Head title="Mes candidatures" />

    <AuthenticatedLayout>
        <PageHeader title="Mes candidatures" description="Suivez l’avancement de chacune de vos candidatures." />

        <div v-if="applications.length > 0" class="mt-6 grid grid-cols-2 gap-3 sm:max-w-xl">
            <StatCard tone="primary" icon="send" :value="applications.length" label="Candidatures envoyées" />
            <StatCard tone="accent" icon="calendar" :value="interviewCount" label="Au stade de l’entretien" />
        </div>

        <EmptyState
            v-if="applications.length === 0"
            class="mt-8"
            icon="send"
            title="Vous n’avez pas encore postulé"
            description="Parcourez les offres disponibles et postulez en un clic."
        >
            <Button :href="route('jobs.index')" icon="search">Explorer les offres</Button>
        </EmptyState>

        <template v-else>
            <Tabs v-model="activeTab" :options="tabs" label="Filtrer les candidatures" class="mt-8" />

            <ul class="mt-4 space-y-3">
                <li
                    v-for="application in groups[activeTab]"
                    :key="application.id"
                    class="rounded-2xl border border-gray-200 bg-white p-4 shadow-card sm:p-5"
                >
                    <div class="flex items-start gap-3">
                        <Avatar :name="application.job_offer.company.name" square />
                        <div class="min-w-0 flex-1">
                            <Link
                                :href="route('jobs.show', application.job_offer.id)"
                                class="font-heading text-base font-bold text-ink hover:text-primary-700 hover:underline"
                            >
                                {{ application.job_offer.title }}
                            </Link>
                            <p class="text-sm text-gray-600">
                                {{ application.job_offer.company.name }} · {{ application.job_offer.location }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-gray-100 pt-3">
                        <Badge :tone="applicationStatuses[application.status].tone" dot>
                            {{ applicationStatuses[application.status].label }}
                        </Badge>
                        <p class="text-xs text-gray-600">
                            <template v-if="finished.includes(application.status)">
                                Mise à jour le {{ formatDate(application.updated_at) }}
                            </template>
                            <template v-else>
                                Envoyée le {{ formatDate(application.applied_at) }}
                            </template>
                        </p>
                    </div>
                </li>
                <li v-if="groups[activeTab].length === 0" class="py-6 text-center text-sm text-gray-600">
                    Aucune candidature dans cette catégorie.
                </li>
            </ul>
        </template>
    </AuthenticatedLayout>
</template>
