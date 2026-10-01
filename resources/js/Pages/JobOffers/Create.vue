<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import JobOfferForm from '@/Components/JobOfferForm.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    location: '',
    contract_type: 'CDI',
    salary_min: '',
    salary_max: '',
    show_salary: false,
    stage_duration_months: '',
    stage_stipend: '',
    guaranteed_response: false,
    skills: [],
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        skills: data.skills
            .map((skill) => skill.trim())
            .filter(Boolean),
    })).post(route('job-offers.store'));
};
</script>

<template>
    <Head title="Créer une offre" />

    <AuthenticatedLayout>
        <PageHeader
            eyebrow="Mes offres · Nouvelle offre"
            title="Créer une offre d’emploi"
            description="Remplissez les informations ci-dessous pour attirer les bons profils."
            class="mb-6"
        />

        <JobOfferForm :form="form" submit-label="Soumettre l’offre" moderation-notice @submit="submit" />
    </AuthenticatedLayout>
</template>
