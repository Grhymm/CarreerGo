<script setup>
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    skills: Array,
});

const newSkill = ref('');

const form = useForm({
    skills: props.skills.map((skill) => skill.name),
});

const sync = () => {
    form.put(route('profile.skills.update'), { preserveScroll: true });
};

const addSkill = () => {
    const name = newSkill.value.trim();

    if (name && !form.skills.includes(name)) {
        form.skills.push(name);
        sync();
    }

    newSkill.value = '';
};

const removeSkill = (name) => {
    form.skills = form.skills.filter((skill) => skill !== name);
    sync();
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Compétences</h2>
            <p class="mt-1 text-sm text-gray-600">
                Ajoutez les compétences qui vous représentent le mieux.
            </p>
        </header>

        <div class="mt-6 flex flex-wrap gap-2">
            <span
                v-for="skill in form.skills"
                :key="skill"
                class="inline-flex items-center gap-2 rounded-full bg-tertiary-50 px-3 py-1 text-sm text-tertiary-700"
            >
                {{ skill }}
                <button
                    type="button"
                    class="text-tertiary-400 hover:text-tertiary-800"
                    @click="removeSkill(skill)"
                >
                    &times;
                </button>
            </span>
            <span v-if="form.skills.length === 0" class="text-sm text-gray-500">
                Aucune compétence ajoutée pour l'instant.
            </span>
        </div>

        <form @submit.prevent="addSkill" class="mt-4 flex items-end gap-3">
            <div class="flex-1">
                <TextInput
                    id="new_skill"
                    type="text"
                    class="block w-full"
                    v-model="newSkill"
                    placeholder="Ex. Laravel"
                />
                <InputError class="mt-2" :message="form.errors.skills" />
            </div>

            <SecondaryButton type="submit">Ajouter</SecondaryButton>
        </form>
    </section>
</template>
