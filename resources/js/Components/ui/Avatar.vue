<script setup>
import { initials } from '@/utils/format';
import { computed } from 'vue';

const props = defineProps({
    name: {
        type: String,
        default: '',
    },
    size: {
        type: String,
        default: 'md',
    },
    square: {
        type: Boolean,
        default: false,
    },
});

const sizes = {
    sm: 'h-8 w-8 text-xs',
    md: 'h-10 w-10 text-sm',
    lg: 'h-14 w-14 text-lg',
    xl: 'h-24 w-24 text-3xl',
};

const palettes = [
    'bg-primary-600 text-white',
    'bg-tertiary-600 text-white',
    'bg-secondary-600 text-ink',
    'bg-sand text-ink',
];

const palette = computed(() => {
    const sum = [...props.name].reduce((total, char) => total + char.charCodeAt(0), 0);

    return palettes[sum % palettes.length];
});
</script>

<template>
    <span
        class="inline-flex shrink-0 select-none items-center justify-center font-heading font-bold"
        :class="[sizes[size], palette, square ? 'rounded-xl' : 'rounded-full']"
        aria-hidden="true"
    >
        {{ initials(name) || '?' }}
    </span>
</template>
