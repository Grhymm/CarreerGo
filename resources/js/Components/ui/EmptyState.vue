<script setup>
import Icon from '@/Components/ui/Icon.vue';

defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: null,
    },
    icon: {
        type: String,
        default: 'inbox',
    },
});
</script>

<template>
    <div class="flex flex-col items-center rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-10 text-center">
        <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-sand text-primary-700">
            <Icon :name="icon" size="lg" />
        </span>
        <p class="mt-4 font-heading text-lg font-bold text-ink">{{ title }}</p>
        <p v-if="description" class="mt-1 max-w-sm text-sm text-gray-600">{{ description }}</p>
        <div v-if="$slots.default" class="mt-5">
            <slot />
        </div>
    </div>
</template>
