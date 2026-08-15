<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { router, useForm } from '@inertiajs/vue3';

defineProps({
    experiences: Array,
});

const form = useForm({
    title: '',
    company_name: '',
    start_date: '',
    end_date: '',
});

const addExperience = () => {
    form.post(route('experiences.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const removeExperience = (experience) => {
    router.delete(route('experiences.destroy', experience.id), { preserveScroll: true });
};

const formatDate = (date) => (date ? new Date(date).toLocaleDateString('fr-FR') : null);
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Expériences</h2>
            <p class="mt-1 text-sm text-gray-600">
                Renseignez vos expériences professionnelles.
            </p>
        </header>

        <ul class="mt-6 space-y-3">
            <li
                v-for="experience in experiences"
                :key="experience.id"
                class="flex items-center justify-between rounded-md border border-gray-200 px-4 py-3"
            >
                <div>
                    <p class="font-medium text-gray-900">{{ experience.title }}</p>
                    <p class="text-sm text-gray-600">
                        {{ experience.company_name }} —
                        {{ formatDate(experience.start_date) }} à
                        {{ formatDate(experience.end_date) ?? "aujourd'hui" }}
                    </p>
                </div>
                <DangerButton @click="removeExperience(experience)">Supprimer</DangerButton>
            </li>
            <li v-if="experiences.length === 0" class="text-sm text-gray-500">
                Aucune expérience ajoutée pour l'instant.
            </li>
        </ul>

        <form @submit.prevent="addExperience" class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <InputLabel for="exp_title" value="Titre du poste" />
                <TextInput id="exp_title" type="text" class="mt-1 block w-full" v-model="form.title" required />
                <InputError class="mt-2" :message="form.errors.title" />
            </div>

            <div>
                <InputLabel for="exp_company_name" value="Entreprise" />
                <TextInput id="exp_company_name" type="text" class="mt-1 block w-full" v-model="form.company_name" required />
                <InputError class="mt-2" :message="form.errors.company_name" />
            </div>

            <div>
                <InputLabel for="exp_start_date" value="Date de début" />
                <TextInput id="exp_start_date" type="date" class="mt-1 block w-full" v-model="form.start_date" required />
                <InputError class="mt-2" :message="form.errors.start_date" />
            </div>

            <div>
                <InputLabel for="exp_end_date" value="Date de fin (optionnelle)" />
                <TextInput id="exp_end_date" type="date" class="mt-1 block w-full" v-model="form.end_date" />
                <InputError class="mt-2" :message="form.errors.end_date" />
            </div>

            <div class="sm:col-span-2">
                <SecondaryButton type="submit" :disabled="form.processing">Ajouter l'expérience</SecondaryButton>
            </div>
        </form>
    </section>
</template>
