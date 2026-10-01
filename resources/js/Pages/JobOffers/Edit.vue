<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import JobOfferForm from '@/Components/JobOfferForm.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    jobOffer: Object,
});

const form = useForm({
    title: props.jobOffer.title,
    description: props.jobOffer.description,
    location: props.jobOffer.location,
    contract_type: props.jobOffer.contract_type,
    salary_min: props.jobOffer.salary_min ?? '',
    salary_max: props.jobOffer.salary_max ?? '',
    show_salary: props.jobOffer.show_salary,
    stage_duration_months: props.jobOffer.stage_duration_months ?? '',
    stage_stipend: props.jobOffer.stage_stipend ?? '',
    guaranteed_response: props.jobOffer.guaranteed_response,
    skills: props.jobOffer.skills.map((skill) => skill.name),
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        skills: data.skills
            .map((skill) => skill.trim())
            .filter(Boolean),
    })).put(route('job-offers.update', props.jobOffer.id));
};
</script>

<template>
    <Head title="Modifier l'offre" />

    <AuthenticatedLayout>
        <PageHeader
            eyebrow="Mes offres · Modification"
            :title="jobOffer.title"
            description="Mettez à jour les informations de votre offre."
            class="mb-6"
        />

        <JobOfferForm :form="form" submit-label="Enregistrer les modifications" @submit="submit" />
    </AuthenticatedLayout>
</template>
