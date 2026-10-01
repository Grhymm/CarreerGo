<script setup>
import Icon from '@/Components/ui/Icon.vue';

defineProps({
    label: {
        type: String,
        required: true,
    },
    value: {
        type: [Number, String],
        required: true,
    },
    icon: {
        type: String,
        default: null,
    },
    tone: {
        type: String,
        default: 'light',
    },
});

const tones = {
    light: 'border border-gray-200 bg-white text-ink',
    primary: 'bg-primary-600 text-white',
    tertiary: 'bg-tertiary-700 text-white',
    accent: 'bg-secondary-600 text-ink',
};

const iconTones = {
    light: 'bg-primary-50 text-primary-700',
    primary: 'bg-white/10 text-white',
    tertiary: 'bg-white/10 text-white',
    accent: 'bg-white/30 text-ink',
};
</script>

<template>
    <div class="flex items-center gap-4 rounded-2xl p-4 shadow-card sm:p-5" :class="tones[tone]">
        <span v-if="icon" class="inline-flex h-10 w-10 items-center justify-center rounded-xl" :class="iconTones[tone]">
            <Icon :name="icon" />
        </span>
        <div class="min-w-0">
            <p class="font-heading text-2xl font-bold leading-none">{{ value }}</p>
            <p class="mt-1 text-sm">{{ label }}</p>
        </div>
    </div>
</template>
