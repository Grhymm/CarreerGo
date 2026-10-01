<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    eyebrow: {
        type: String,
        default: null,
    },
    description: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            <p v-if="eyebrow" class="mb-1 text-sm font-semibold text-tertiary-700">
                {{ eyebrow }}
            </p>
            <h1 class="text-2xl font-bold leading-tight text-ink sm:text-3xl">
                {{ title }}
            </h1>
            <p v-if="description" class="mt-2 max-w-2xl text-sm text-gray-600 sm:text-base">
                {{ description }}
            </p>
        </div>

        <div v-if="$slots.actions" class="flex flex-wrap items-center gap-2">
            <slot name="actions" />
        </div>
    </header>
</template>
