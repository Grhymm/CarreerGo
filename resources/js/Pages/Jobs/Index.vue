<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import Badge from '@/Components/ui/Badge.vue';
import Button from '@/Components/ui/Button.vue';
import ChoiceChips from '@/Components/ui/ChoiceChips.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/ui/Icon.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import { formatAmount, formatRelative, formatSalaryRange, isRecent } from '@/utils/format';
import { contractTypes } from '@/utils/status';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    jobOffers: Array,
    filters: Object,
});

const form = useForm({
    keyword: props.filters.keyword || '',
    location: props.filters.location || '',
    contract_type: props.filters.contract_type || '',
});

const showFilters = ref(Boolean(props.filters.location));

const contractOptions = [
    { value: '', label: 'Tous' },
    ...contractTypes.map((type) => ({ value: type, label: type })),
];

const search = () => {
    router.get(route('jobs.index'), form.data(), { preserveState: true });
};

const resetFilters = () => {
    form.keyword = '';
    form.location = '';
    form.contract_type = '';
    search();
};
</script>

<template>
    <Head title="Offres d'emploi" />

    <AuthenticatedLayout>
        <PageHeader title="Explorer les offres" description="Trouvez l’emploi ou le stage qui vous correspond." />

        <form class="mt-6 space-y-4" role="search" @submit.prevent="search">
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <label for="keyword" class="sr-only">Poste recherché</label>
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-600">
                        <Icon name="search" />
                    </span>
                    <input
                        id="keyword"
                        v-model="form.keyword"
                        type="search"
                        class="ui-input min-h-12 pl-10"
                        placeholder="Poste, métier…"
                    />
                </div>
                <button
                    type="button"
                    class="inline-flex min-h-12 min-w-12 items-center justify-center rounded-lg transition-colors"
                    :class="showFilters ? 'bg-secondary-600 text-ink' : 'border border-gray-300 bg-white text-ink hover:border-primary-600'"
                    :aria-expanded="showFilters"
                    aria-controls="filtres-avances"
                    @click="showFilters = !showFilters"
                >
                    <Icon name="filter" />
                    <span class="sr-only">Afficher les filtres</span>
                </button>
                <Button type="submit" size="lg" class="hidden sm:inline-flex">Rechercher</Button>
            </div>

            <div v-show="showFilters" id="filtres-avances">
                <label for="location" class="ui-label-caps">Ville ou région</label>
                <div class="relative mt-1.5">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-600">
                        <Icon name="pin" />
                    </span>
                    <input
                        id="location"
                        v-model="form.location"
                        type="text"
                        class="ui-input pl-10"
                        placeholder="Libreville, Port-Gentil…"
                    />
                </div>
            </div>

            <ChoiceChips
                v-model="form.contract_type"
                :options="contractOptions"
                name="contract_type"
                legend="Type de contrat"
                hide-legend
                @select="search"
            />

            <Button type="submit" block class="sm:hidden">Rechercher</Button>
        </form>

        <section class="mt-8" aria-labelledby="resultats">
            <div class="mb-4 flex items-baseline justify-between">
                <h2 id="resultats" class="text-xl font-bold">Offres disponibles</h2>
                <p class="text-sm text-gray-600" aria-live="polite">
                    {{ jobOffers.length }} résultat{{ jobOffers.length > 1 ? 's' : '' }}
                </p>
            </div>

            <EmptyState
                v-if="jobOffers.length === 0"
                icon="search"
                title="Aucune offre ne correspond à votre recherche"
                description="Essayez un autre mot-clé ou élargissez vos filtres."
            >
                <Button variant="outlined" @click="resetFilters">Effacer les filtres</Button>
            </EmptyState>

            <ul v-else class="grid gap-4 md:grid-cols-2">
                <li v-for="jobOffer in jobOffers" :key="jobOffer.id">
                    <Link
                        :href="route('jobs.show', jobOffer.id)"
                        class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-4 shadow-card transition-colors hover:border-primary-600 sm:p-5"
                    >
                        <div class="flex items-start gap-3">
                            <Avatar :name="jobOffer.company.name" square />
                            <div class="min-w-0 flex-1">
                                <div class="mb-1 flex flex-wrap gap-1.5">
                                    <Badge v-if="isRecent(jobOffer.created_at)" tone="tertiary">Nouveau</Badge>
                                    <Badge v-if="jobOffer.guaranteed_response" tone="accent">Réponse garantie</Badge>
                                </div>
                                <h3 class="font-heading text-lg font-bold leading-snug text-ink">{{ jobOffer.title }}</h3>
                                <p class="mt-0.5 flex flex-wrap items-center gap-x-2 text-sm text-gray-600">
                                    {{ jobOffer.company.name }}
                                    <span v-if="jobOffer.company.is_verified" class="inline-flex items-center gap-1 text-primary-700">
                                        <Icon name="badgeCheck" size="sm" />
                                        Entreprise vérifiée
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2 text-sm">
                            <span class="inline-flex items-center gap-1 rounded-lg bg-sand px-2.5 py-1 text-ink">
                                <Icon name="pin" size="sm" />
                                {{ jobOffer.location }}
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-lg bg-sand px-2.5 py-1 text-ink">
                                <Icon name="briefcase" size="sm" />
                                {{ jobOffer.contract_type }}
                            </span>
                        </div>

                        <div class="mt-auto flex flex-wrap items-end justify-between gap-2 pt-4">
                            <p class="font-semibold text-primary-700">
                                <template v-if="jobOffer.contract_type === 'Stage' && jobOffer.stage_duration_months">
                                    Stage de {{ jobOffer.stage_duration_months }} mois
                                    <span v-if="jobOffer.stage_stipend" class="font-normal text-gray-600">
                                        · {{ formatAmount(jobOffer.stage_stipend) }} FCFA / mois
                                    </span>
                                </template>
                                <template v-else-if="jobOffer.show_salary && formatSalaryRange(jobOffer.salary_min, jobOffer.salary_max)">
                                    {{ formatSalaryRange(jobOffer.salary_min, jobOffer.salary_max) }}
                                </template>
                            </p>
                            <p class="text-xs text-gray-600">Déposée {{ formatRelative(jobOffer.created_at) }}</p>
                        </div>
                    </Link>
                </li>
            </ul>
        </section>
    </AuthenticatedLayout>
</template>
