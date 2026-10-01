<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="flex w-full items-center gap-2 px-4 py-2.5 text-start text-sm text-ink transition-colors hover:bg-sand focus:bg-sand"
    >
        <slot />
    </Link>
</template>
