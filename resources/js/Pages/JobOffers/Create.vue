<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
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
    skills: '',
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        skills: data.skills
            .split(',')
            .map((skill) => skill.trim())
            .filter(Boolean),
    })).post(route('job-offers.store'));
};
</script>

<template>
    <Head title="Créer une offre" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Créer une offre d'emploi
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="title" value="Titre" />
                            <TextInput
                                id="title"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.title"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="description" value="Description" />
                            <textarea
                                id="description"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                rows="5"
                                v-model="form.description"
                                required
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="location" value="Localisation" />
                            <TextInput
                                id="location"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.location"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.location" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="contract_type" value="Type de contrat" />
                            <select
                                id="contract_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                v-model="form.contract_type"
                                required
                            >
                                <option value="CDI">CDI</option>
                                <option value="CDD">CDD</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Stage">Stage</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.contract_type" />
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="salary_min" value="Salaire min" />
                                <TextInput
                                    id="salary_min"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.salary_min"
                                />
                                <InputError class="mt-2" :message="form.errors.salary_min" />
                            </div>

                            <div>
                                <InputLabel for="salary_max" value="Salaire max" />
                                <TextInput
                                    id="salary_max"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.salary_max"
                                />
                                <InputError class="mt-2" :message="form.errors.salary_max" />
                            </div>
                        </div>

                        <div class="mt-4 flex items-center">
                            <Checkbox
                                id="show_salary"
                                v-model:checked="form.show_salary"
                            />
                            <label for="show_salary" class="ms-2 text-sm text-gray-600">
                                Afficher le salaire publiquement
                            </label>
                        </div>

                        <div v-if="form.contract_type === 'Stage'" class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="stage_duration_months" value="Durée du stage (mois)" />
                                <TextInput
                                    id="stage_duration_months"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.stage_duration_months"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.stage_duration_months" />
                            </div>

                            <div>
                                <InputLabel for="stage_stipend" value="Gratification (par mois)" />
                                <TextInput
                                    id="stage_stipend"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.stage_stipend"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.stage_stipend" />
                            </div>
                        </div>

                        <div class="mt-4 flex items-center">
                            <Checkbox
                                id="guaranteed_response"
                                v-model:checked="form.guaranteed_response"
                            />
                            <label for="guaranteed_response" class="ms-2 text-sm text-gray-600">
                                Réponse garantie aux candidats
                            </label>
                        </div>

                        <div class="mt-4">
                            <InputLabel for="skills" value="Compétences (séparées par des virgules)" />
                            <TextInput
                                id="skills"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.skills"
                                placeholder="PHP, Laravel, Vue.js"
                            />
                            <InputError class="mt-2" :message="form.errors.skills" />
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Créer l'offre
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
