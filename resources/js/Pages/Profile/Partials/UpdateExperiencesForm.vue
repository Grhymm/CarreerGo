<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Button from '@/Components/ui/Button.vue';
import Icon from '@/Components/ui/Icon.vue';
import { formatDate } from '@/utils/format';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    experiences: Array,
});

const showForm = ref(props.experiences.length === 0);

const form = useForm({
    title: '',
    company_name: '',
    start_date: '',
    end_date: '',
});

const addExperience = () => {
    form.post(route('experiences.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showForm.value = false;
        },
    });
};

const removeExperience = (experience) => {
    router.delete(route('experiences.destroy', experience.id), { preserveScroll: true });
};

const period = (experience) => {
    const options = { month: 'short', year: 'numeric' };

    return `${formatDate(experience.start_date, options)} – ${formatDate(experience.end_date, options) ?? 'aujourd’hui'}`;
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-bold">Expériences</h2>
            <p class="mt-1 text-sm text-gray-600">Renseignez vos expériences professionnelles.</p>
        </header>

        <ul class="mt-4 space-y-3">
            <li
                v-for="experience in experiences"
                :key="experience.id"
                class="flex items-start gap-3 rounded-xl border border-gray-200 p-4"
            >
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-sand text-primary-700">
                    <Icon name="briefcase" />
                </span>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-ink">{{ experience.title }}</p>
                    <p class="text-sm text-primary-700">{{ experience.company_name }}</p>
                    <p class="mt-0.5 text-xs text-gray-600">{{ period(experience) }}</p>
                </div>
                <button
                    type="button"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-lg text-gray-600 hover:bg-red-50 hover:text-red-700"
                    @click="removeExperience(experience)"
                >
                    <Icon name="trash" />
                    <span class="sr-only">Supprimer l’expérience {{ experience.title }}</span>
                </button>
            </li>
            <li v-if="experiences.length === 0" class="text-sm text-gray-600">
                Aucune expérience ajoutée pour l’instant.
            </li>
        </ul>

        <button
            v-if="!showForm"
            type="button"
            class="mt-4 flex min-h-12 w-full items-center justify-center gap-2 rounded-xl border border-dashed border-gray-400 text-sm font-semibold text-primary-700 hover:border-primary-600 hover:bg-primary-50"
            @click="showForm = true"
        >
            <Icon name="plus" size="sm" />
            Ajouter une expérience
        </button>

        <form v-else class="mt-4 grid grid-cols-1 gap-4 rounded-xl bg-sand p-4 sm:grid-cols-2" @submit.prevent="addExperience">
            <div>
                <InputLabel for="exp_title" value="Intitulé du poste" />
                <TextInput id="exp_title" v-model="form.title" type="text" class="mt-1.5" required />
                <InputError class="mt-2" :message="form.errors.title" />
            </div>

            <div>
                <InputLabel for="exp_company_name" value="Entreprise" />
                <TextInput id="exp_company_name" v-model="form.company_name" type="text" class="mt-1.5" required />
                <InputError class="mt-2" :message="form.errors.company_name" />
            </div>

            <div>
                <InputLabel for="exp_start_date" value="Date de début" />
                <TextInput id="exp_start_date" v-model="form.start_date" type="date" class="mt-1.5" required />
                <InputError class="mt-2" :message="form.errors.start_date" />
            </div>

            <div>
                <InputLabel for="exp_end_date" value="Date de fin (laissez vide si en cours)" />
                <TextInput id="exp_end_date" v-model="form.end_date" type="date" class="mt-1.5" />
                <InputError class="mt-2" :message="form.errors.end_date" />
            </div>

            <div class="flex flex-wrap gap-2 sm:col-span-2">
                <Button type="submit" :loading="form.processing">Ajouter l’expérience</Button>
                <Button v-if="experiences.length > 0" variant="ghost" @click="showForm = false">Annuler</Button>
            </div>
        </form>
    </section>
</template>
