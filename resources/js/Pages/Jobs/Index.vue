<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    jobOffers: Array,
    filters: Object,
});

const form = useForm({
    keyword: props.filters.keyword || '',
    location: props.filters.location || '',
    contract_type: props.filters.contract_type || '',
});

const search = () => {
    router.get(route('jobs.index'), form.data(), { preserveState: true });
};
</script>

<template>
    <Head title="Offres d'emploi" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Offres d'emploi
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <form @submit.prevent="search" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                        <div>
                            <InputLabel for="keyword" value="Mot-clé" />
                            <TextInput
                                id="keyword"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.keyword"
                                placeholder="Titre du poste"
                            />
                        </div>

                        <div>
                            <InputLabel for="location" value="Localisation" />
                            <TextInput
                                id="location"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.location"
                            />
                        </div>

                        <div>
                            <InputLabel for="contract_type" value="Type de contrat" />
                            <select
                                id="contract_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                v-model="form.contract_type"
                            >
                                <option value="">Tous</option>
                                <option value="CDI">CDI</option>
                                <option value="CDD">CDD</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Stage">Stage</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <PrimaryButton class="w-full justify-center">Rechercher</PrimaryButton>
                        </div>
                    </form>
                </div>

                <div class="space-y-4">
                    <div v-if="jobOffers.length === 0" class="bg-white p-6 text-center text-gray-500 shadow-sm sm:rounded-lg">
                        Aucune offre ne correspond à votre recherche.
                    </div>

                    <Link
                        v-for="jobOffer in jobOffers"
                        :key="jobOffer.id"
                        :href="route('jobs.show', jobOffer.id)"
                        class="block bg-white p-6 shadow-sm transition hover:shadow-md sm:rounded-lg"
                    >
                        <h3 class="text-lg font-semibold text-gray-900">{{ jobOffer.title }}</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ jobOffer.company.name }} — {{ jobOffer.location }}
                        </p>
                        <span class="mt-2 inline-block rounded-full bg-tertiary-50 px-3 py-1 text-xs font-medium text-tertiary-700">
                            {{ jobOffer.contract_type }}
                        </span>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
