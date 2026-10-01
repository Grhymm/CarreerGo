<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import Badge from '@/Components/ui/Badge.vue';
import Button from '@/Components/ui/Button.vue';
import Card from '@/Components/ui/Card.vue';
import Icon from '@/Components/ui/Icon.vue';
import ProgressBar from '@/Components/ui/ProgressBar.vue';
import { formatAmount, formatDate, formatSalaryRange } from '@/utils/format';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    jobOffer: Object,
    compatibilityScore: Number,
    hasApplied: Boolean,
});

const page = usePage();

const isCandidat = computed(() => page.props.auth.user.role === 'candidat');

const applying = ref(false);

const apply = () => {
    router.post(route('applications.store', props.jobOffer.id), {}, {
        onStart: () => (applying.value = true),
        onFinish: () => (applying.value = false),
    });
};

const showReportForm = ref(false);

const reportForm = useForm({
    reason: '',
});

const submitReport = () => {
    reportForm.post(route('reports.store', props.jobOffer.id), {
        preserveScroll: true,
        onSuccess: () => {
            reportForm.reset();
            showReportForm.value = false;
        },
    });
};

const facts = computed(() => {
    const offer = props.jobOffer;
    const items = [{ label: 'Contrat', value: offer.contract_type }];

    if (offer.contract_type === 'Stage') {
        if (offer.stage_duration_months) {
            items.push({ label: 'Durée', value: `${offer.stage_duration_months} mois` });
        }
        if (offer.stage_stipend) {
            items.push({ label: 'Gratification', value: `${formatAmount(offer.stage_stipend)} FCFA / mois` });
        }
    }

    const salary = offer.show_salary ? formatSalaryRange(offer.salary_min, offer.salary_max) : null;

    if (salary) {
        items.push({ label: 'Rémunération', value: salary });
    }

    return items;
});
</script>

<template>
    <Head :title="jobOffer.title" />

    <AuthenticatedLayout>
        <Link
            :href="route('jobs.index')"
            class="mb-4 inline-flex min-h-11 items-center gap-1 text-sm font-semibold text-tertiary-700 hover:underline"
        >
            <Icon name="chevronLeft" size="sm" />
            Retour aux offres
        </Link>

        <div class="grid gap-6 lg:grid-cols-[1fr_20rem]">
            <div class="space-y-6">
                <Card padding="lg">
                    <div class="flex items-start justify-between gap-4">
                        <Avatar :name="jobOffer.company.name" size="lg" square />
                        <Badge v-if="jobOffer.guaranteed_response" tone="accent" icon="check">Réponse garantie</Badge>
                    </div>

                    <h1 class="mt-4 text-2xl font-bold leading-tight sm:text-3xl">{{ jobOffer.title }}</h1>

                    <div class="mt-3 flex flex-col gap-2 text-sm text-gray-600 sm:flex-row sm:flex-wrap sm:gap-x-5">
                        <span class="inline-flex items-center gap-1.5">
                            <Icon name="building" size="sm" />
                            {{ jobOffer.company.name }}
                            <span v-if="jobOffer.company.is_verified" class="inline-flex items-center gap-1 font-semibold text-primary-700">
                                <Icon name="badgeCheck" size="sm" />
                                Entreprise vérifiée
                            </span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <Icon name="pin" size="sm" />
                            {{ jobOffer.location }}
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <Icon name="calendar" size="sm" />
                            Déposée le {{ formatDate(jobOffer.created_at, { day: 'numeric', month: 'long', year: 'numeric' }) }}
                        </span>
                    </div>

                    <dl class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        <div v-for="fact in facts" :key="fact.label" class="rounded-xl bg-sand px-4 py-3">
                            <dt class="ui-label-caps">{{ fact.label }}</dt>
                            <dd class="mt-1 font-heading font-bold text-ink">{{ fact.value }}</dd>
                        </div>
                    </dl>
                </Card>

                <Card v-if="isCandidat" padding="lg">
                    <h2 class="text-lg font-bold">Votre compatibilité</h2>
                    <template v-if="jobOffer.skills.length > 0">
                        <div class="mt-3 flex items-center gap-3">
                            <ProgressBar :value="compatibilityScore" label="Score de compatibilité" class="flex-1" />
                            <span class="font-heading text-lg font-bold text-primary-700">{{ compatibilityScore }} %</span>
                        </div>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ compatibilityScore }} % des compétences demandées figurent sur votre profil.
                            <Link :href="route('profile.edit')" class="font-semibold text-primary-700 underline-offset-2 hover:underline">
                                Mettre à jour mes compétences
                            </Link>
                        </p>
                    </template>
                    <p v-else class="mt-2 text-sm text-gray-600">
                        Cette offre ne précise aucune compétence : nous ne pouvons pas calculer de score de compatibilité.
                    </p>
                </Card>

                <Card padding="lg">
                    <h2 class="text-lg font-bold">Description du poste</h2>
                    <p class="mt-3 whitespace-pre-line leading-relaxed text-ink">{{ jobOffer.description }}</p>

                    <template v-if="jobOffer.skills.length > 0">
                        <h2 class="mt-8 text-lg font-bold">Compétences requises</h2>
                        <ul class="mt-3 flex flex-wrap gap-2">
                            <li
                                v-for="skill in jobOffer.skills"
                                :key="skill.id"
                                class="rounded-lg bg-tertiary-50 px-3 py-1.5 text-sm font-semibold text-tertiary-800"
                            >
                                {{ skill.name }}
                            </li>
                        </ul>
                    </template>
                </Card>

                <div v-if="isCandidat" class="px-1">
                    <button
                        type="button"
                        class="inline-flex min-h-11 items-center gap-2 text-sm font-semibold text-gray-600 hover:text-ink"
                        :aria-expanded="showReportForm"
                        aria-controls="formulaire-signalement"
                        @click="showReportForm = !showReportForm"
                    >
                        <Icon name="flag" size="sm" />
                        Signaler cette offre
                    </button>

                    <form
                        v-if="showReportForm"
                        id="formulaire-signalement"
                        class="mt-2 max-w-lg space-y-3"
                        @submit.prevent="submitReport"
                    >
                        <label for="reason" class="text-sm font-semibold text-ink">Pourquoi cette offre vous paraît-elle suspecte ?</label>
                        <textarea
                            id="reason"
                            v-model="reportForm.reason"
                            class="ui-input"
                            rows="3"
                            placeholder="Décrivez ce qui vous a alerté…"
                            required
                        ></textarea>
                        <InputError :message="reportForm.errors.reason" />
                        <Button type="submit" variant="outlined" :loading="reportForm.processing">
                            Envoyer le signalement
                        </Button>
                    </form>
                </div>
            </div>

            <aside v-if="isCandidat" class="lg:sticky lg:top-24 lg:self-start">
                <div
                    class="fixed inset-x-0 bottom-[calc(4rem+env(safe-area-inset-bottom))] z-20 border-t border-gray-200 bg-white p-4 lg:static lg:rounded-2xl lg:border lg:p-5 lg:shadow-card"
                >
                    <p class="mb-3 hidden text-sm text-gray-600 lg:block">
                        Une fois envoyée, votre candidature apparaît dans l’onglet « Candidatures ».
                    </p>
                    <Button v-if="!hasApplied" block size="lg" icon="send" :loading="applying" @click="apply">
                        Postuler en un clic
                    </Button>
                    <Button v-else block size="lg" variant="secondary" icon="check" disabled>
                        Candidature envoyée
                    </Button>
                </div>
            </aside>
        </div>

        <div v-if="isCandidat" class="h-24 lg:hidden" aria-hidden="true"></div>
    </AuthenticatedLayout>
</template>
