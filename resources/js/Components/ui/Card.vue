<script setup>
defineProps({
    as: {
        type: String,
        default: 'div',
    },
    padding: {
        type: String,
        default: 'md',
    },
});

const paddings = {
    none: '',
    sm: 'p-4',
    md: 'p-4 sm:p-6',
    lg: 'p-6 sm:p-8',
};
</script>

<template>
    <component
        :is="as"
        class="rounded-2xl border border-gray-200 bg-white shadow-card"
        :class="paddings[padding]"
    >
        <slot />
    </component>
</template>
