<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/ui/Badge.vue';
import Button from '@/Components/ui/Button.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/ui/Icon.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import { formatDate } from '@/utils/format';
import { jobOfferStatuses } from '@/utils/status';
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
        <PageHeader title="Mes offres d’emploi" description="Retrouvez vos offres et leur statut de publication.">
            <template #actions>
                <Button :href="route('job-offers.create')" icon="plus">Publier une offre</Button>
            </template>
        </PageHeader>

        <EmptyState
            v-if="jobOffers.length === 0"
            class="mt-8"
            icon="briefcase"
            title="Aucune offre pour l’instant"
            description="Publiez votre première offre : notre équipe la vérifiera avant sa mise en ligne."
        >
            <Button :href="route('job-offers.create')" icon="plus">Publier une offre</Button>
        </EmptyState>

        <ul v-else class="mt-8 space-y-3">
            <li
                v-for="jobOffer in jobOffers"
                :key="jobOffer.id"
                class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-4 shadow-card sm:flex-row sm:items-center sm:p-5"
            >
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="font-heading text-base font-bold text-ink">{{ jobOffer.title }}</h2>
                        <Badge :tone="jobOfferStatuses[jobOffer.status]?.tone ?? 'neutral'" dot>
                            {{ jobOfferStatuses[jobOffer.status]?.label ?? jobOffer.status }}
                        </Badge>
                    </div>
                    <p class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600">
                        <span class="inline-flex items-center gap-1"><Icon name="pin" size="sm" />{{ jobOffer.location }}</span>
                        <span class="inline-flex items-center gap-1"><Icon name="briefcase" size="sm" />{{ jobOffer.contract_type }}</span>
                        <span class="inline-flex items-center gap-1"><Icon name="calendar" size="sm" />Créée le {{ formatDate(jobOffer.created_at) }}</span>
                    </p>
                </div>

                <div class="flex gap-2">
                    <Button :href="route('job-offers.edit', jobOffer.id)" variant="outlined" size="sm" icon="pencil">
                        Modifier
                    </Button>
                    <button
                        type="button"
                        class="inline-flex min-h-9 items-center gap-1.5 rounded-lg px-3 text-sm font-semibold text-red-700 hover:bg-red-50"
                        @click="destroy(jobOffer)"
                    >
                        <Icon name="trash" size="sm" />
                        Supprimer
                    </button>
                </div>
            </li>
        </ul>

        <p v-if="jobOffers.length > 0" class="mt-6 text-sm text-gray-600">
            Les candidatures reçues sont à retrouver dans
            <Link :href="route('recruiter.applications.index')" class="font-semibold text-primary-700 underline-offset-2 hover:underline">
                l’onglet Candidatures
            </Link>.
        </p>
    </AuthenticatedLayout>
</template>
