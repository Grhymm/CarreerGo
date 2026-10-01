<script setup>
import Icon from '@/Components/ui/Icon.vue';

defineProps({
    tone: {
        type: String,
        default: 'neutral',
    },
    icon: {
        type: String,
        default: null,
    },
    dot: {
        type: Boolean,
        default: false,
    },
});

const tones = {
    primary: 'bg-primary-50 text-primary-700',
    success: 'bg-primary-100 text-primary-800',
    accent: 'bg-secondary-200 text-ink',
    tertiary: 'bg-tertiary-100 text-tertiary-800',
    danger: 'bg-red-50 text-red-800',
    neutral: 'bg-gray-100 text-gray-700',
};

const dots = {
    primary: 'bg-primary-600',
    success: 'bg-primary-600',
    accent: 'bg-secondary-700',
    tertiary: 'bg-tertiary-600',
    danger: 'bg-red-700',
    neutral: 'bg-gray-500',
};
</script>

<template>
    <span
        class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold"
        :class="tones[tone]"
    >
        <span v-if="dot" class="h-1.5 w-1.5 rounded-full" :class="dots[tone]" aria-hidden="true"></span>
        <Icon v-if="icon" :name="icon" size="xs" />
        <slot />
    </span>
</template>
