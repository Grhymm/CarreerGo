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
        class="inline-flex items-center rounded-md border border-primary-600 bg-transparent px-4 py-2 text-xs font-semibold uppercase tracking-widest text-primary-700 transition duration-150 ease-in-out hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-25"
    >
        <slot />
    </button>
</template>
