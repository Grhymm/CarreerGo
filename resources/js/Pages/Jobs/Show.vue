<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    jobOffer: Object,
    compatibilityScore: Number,
    hasApplied: Boolean,
});

const page = usePage();

const apply = () => {
    router.post(route('applications.store', props.jobOffer.id));
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
</script>

<template>
    <Head :title="jobOffer.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Détail de l'offre
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <Link :href="route('jobs.index')" class="mb-4 inline-block text-sm text-indigo-600 hover:text-indigo-900">
                    &larr; Retour aux offres
                </Link>

                <div
                    v-if="page.props.flash.success"
                    class="mb-4 rounded-md bg-green-100 px-4 py-3 text-sm text-green-700"
                >
                    {{ page.props.flash.success }}
                </div>
                <div
                    v-if="page.props.flash.error"
                    class="mb-4 rounded-md bg-red-100 px-4 py-3 text-sm text-red-700"
                >
                    {{ page.props.flash.error }}
                </div>

                <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ jobOffer.title }}</h1>
                            <p class="mt-1 text-gray-600">
                                {{ jobOffer.company.name }}
                                <span
                                    v-if="jobOffer.company.is_verified"
                                    class="ms-2 inline-block rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700"
                                >
                                    Entreprise vérifiée
                                </span>
                            </p>
                        </div>

                        <span
                            v-if="jobOffer.guaranteed_response"
                            class="inline-block rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700"
                        >
                            Réponse garantie
                        </span>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2 text-sm text-gray-600">
                        <span class="rounded-full bg-gray-100 px-3 py-1">{{ jobOffer.location }}</span>
                        <span class="rounded-full bg-gray-100 px-3 py-1">{{ jobOffer.contract_type }}</span>
                        <span v-if="jobOffer.show_salary && (jobOffer.salary_min || jobOffer.salary_max)" class="rounded-full bg-gray-100 px-3 py-1">
                            {{ jobOffer.salary_min ?? '?' }} - {{ jobOffer.salary_max ?? '?' }}
                        </span>
                        <span v-if="jobOffer.contract_type === 'Stage'" class="rounded-full bg-gray-100 px-3 py-1">
                            {{ jobOffer.stage_duration_months }} mois — {{ jobOffer.stage_stipend }}/mois
                        </span>
                    </div>

                    <div class="mt-6">
                        <h2 class="text-sm font-semibold uppercase text-gray-500">Description</h2>
                        <p class="mt-2 whitespace-pre-line text-gray-700">{{ jobOffer.description }}</p>
                    </div>

                    <div class="mt-6" v-if="jobOffer.skills.length > 0">
                        <h2 class="text-sm font-semibold uppercase text-gray-500">Compétences requises</h2>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span
                                v-for="skill in jobOffer.skills"
                                :key="skill.id"
                                class="rounded-full bg-indigo-100 px-3 py-1 text-sm text-indigo-700"
                            >
                                {{ skill.name }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h2 class="text-sm font-semibold uppercase text-gray-500">Score de compatibilité</h2>
                        <div class="mt-2 flex items-center gap-3">
                            <div class="h-2.5 w-full rounded-full bg-gray-200">
                                <div
                                    class="h-2.5 rounded-full bg-indigo-600"
                                    :style="{ width: compatibilityScore + '%' }"
                                ></div>
                            </div>
                            <span class="whitespace-nowrap text-sm font-medium text-gray-700">
                                {{ compatibilityScore }}%
                            </span>
                        </div>
                    </div>

                    <div class="mt-6" v-if="page.props.auth.user.role === 'candidat'">
                        <PrimaryButton v-if="!hasApplied" @click="apply">
                            Postuler en un clic
                        </PrimaryButton>
                        <PrimaryButton v-else disabled class="opacity-50">
                            Déjà postulé
                        </PrimaryButton>
                    </div>

                    <div class="mt-4" v-if="page.props.auth.user.role === 'candidat'">
                        <button
                            type="button"
                            class="text-xs text-gray-400 underline hover:text-gray-600"
                            @click="showReportForm = !showReportForm"
                        >
                            Signaler cette offre
                        </button>

                        <form v-if="showReportForm" @submit.prevent="submitReport" class="mt-3 max-w-md">
                            <textarea
                                class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                rows="3"
                                v-model="reportForm.reason"
                                placeholder="Expliquez pourquoi cette offre vous paraît suspecte..."
                                required
                            ></textarea>
                            <InputError class="mt-2" :message="reportForm.errors.reason" />

                            <SecondaryButton
                                type="submit"
                                class="mt-2"
                                :disabled="reportForm.processing"
                            >
                                Envoyer le signalement
                            </SecondaryButton>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
