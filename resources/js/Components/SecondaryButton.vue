<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 text-sm font-semibold text-ink transition-colors hover:border-primary-600 hover:text-primary-700 disabled:cursor-not-allowed disabled:opacity-50"
    >
        <slot />
    </button>
</template>
