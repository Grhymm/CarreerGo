<script setup>
import { computed } from 'vue';

const props = defineProps({
    value: {
        type: Number,
        default: 0,
    },
    label: {
        type: String,
        required: true,
    },
    tone: {
        type: String,
        default: 'primary',
    },
});

const tones = {
    primary: 'bg-primary-600',
    accent: 'bg-secondary-700',
    tertiary: 'bg-tertiary-600',
};

const clamped = computed(() => Math.min(100, Math.max(0, Math.round(props.value ?? 0))));
</script>

<template>
    <div
        class="h-2 w-full overflow-hidden rounded-full bg-gray-200"
        role="progressbar"
        :aria-label="label"
        :aria-valuenow="clamped"
        aria-valuemin="0"
        aria-valuemax="100"
    >
        <div
            class="h-full rounded-full transition-[width] duration-500"
            :class="tones[tone]"
            :style="{ width: clamped + '%' }"
        ></div>
    </div>
</template>
