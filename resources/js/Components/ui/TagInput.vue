<script setup>
import Icon from '@/Components/ui/Icon.vue';
import { ref } from 'vue';

const model = defineModel({ type: Array, required: true });

defineProps({
    id: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    placeholder: {
        type: String,
        default: 'Ajouter une compétence',
    },
    emptyText: {
        type: String,
        default: null,
    },
});

const draft = ref('');

const add = () => {
    const value = draft.value.trim();

    if (value && !model.value.includes(value)) {
        model.value = [...model.value, value];
    }

    draft.value = '';
};

const remove = (value) => {
    model.value = model.value.filter((item) => item !== value);
};
</script>

<template>
    <div>
        <label :for="id" class="sr-only">{{ label }}</label>
        <div class="flex gap-2">
            <input
                :id="id"
                v-model="draft"
                type="text"
                class="ui-input"
                :placeholder="placeholder"
                @keydown.enter.prevent="add"
            />
            <button
                type="button"
                class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-lg border border-gray-300 bg-white text-primary-700 hover:border-primary-600"
                @click="add"
            >
                <Icon name="plus" />
                <span class="sr-only">Ajouter</span>
            </button>
        </div>

        <ul class="mt-3 flex flex-wrap gap-2" :aria-label="label">
            <li
                v-for="item in model"
                :key="item"
                class="inline-flex items-center gap-1 rounded-full bg-primary-50 py-1 pe-1 ps-3 text-sm font-semibold text-primary-800"
            >
                {{ item }}
                <button
                    type="button"
                    class="inline-flex h-7 w-7 items-center justify-center rounded-full hover:bg-primary-100"
                    @click="remove(item)"
                >
                    <Icon name="x" size="xs" />
                    <span class="sr-only">Retirer {{ item }}</span>
                </button>
            </li>
            <li v-if="model.length === 0 && emptyText" class="text-sm text-gray-600">{{ emptyText }}</li>
        </ul>
    </div>
</template>
