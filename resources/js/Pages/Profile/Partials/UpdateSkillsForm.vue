<script setup>
import InputError from '@/Components/InputError.vue';
import TagInput from '@/Components/ui/TagInput.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    skills: Array,
});

const form = useForm({
    skills: props.skills.map((skill) => skill.name),
});

const sync = () => {
    form.put(route('profile.skills.update'), { preserveScroll: true });
};

const skills = computed({
    get: () => form.skills,
    set: (value) => {
        form.skills = value;
        sync();
    },
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-bold">Compétences</h2>
            <p class="mt-1 text-sm text-gray-600">
                Ajoutez les compétences qui vous représentent le mieux : elles servent à calculer votre compatibilité avec les offres.
            </p>
        </header>

        <div class="mt-4">
            <TagInput
                id="new_skill"
                v-model="skills"
                label="Vos compétences"
                placeholder="Ex. Laravel, comptabilité, anglais…"
                empty-text="Aucune compétence ajoutée pour l’instant."
            />
            <InputError class="mt-2" :message="form.errors.skills" />
            <p v-if="form.recentlySuccessful" role="status" class="mt-2 text-sm font-semibold text-primary-700">
                Compétences enregistrées.
            </p>
        </div>
    </section>
</template>
