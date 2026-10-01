<script setup>
const model = defineModel({ type: String, required: true });

defineProps({
    options: {
        type: Array,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div role="group" :aria-label="label" class="flex gap-1 overflow-x-auto border-b border-gray-200">
        <button
            v-for="option in options"
            :key="option.value"
            type="button"
            class="-mb-px inline-flex min-h-11 items-center gap-2 whitespace-nowrap border-b-2 px-3 text-sm font-semibold transition-colors"
            :class="model === option.value
                ? 'border-primary-600 text-primary-700'
                : 'border-transparent text-gray-600 hover:text-ink'"
            :aria-pressed="model === option.value"
            @click="model = option.value"
        >
            {{ option.label }}
            <span
                v-if="option.count !== undefined"
                class="rounded-full px-2 py-0.5 text-xs"
                :class="model === option.value ? 'bg-primary-50 text-primary-700' : 'bg-gray-100 text-gray-700'"
            >
                {{ option.count }}
            </span>
        </button>
    </div>
</template>
