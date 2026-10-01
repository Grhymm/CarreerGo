<script setup>
import Icon from '@/Components/ui/Icon.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
    },
    size: {
        type: String,
        default: 'md',
    },
    href: {
        type: String,
        default: null,
    },
    native: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String,
        default: 'button',
    },
    icon: {
        type: String,
        default: null,
    },
    block: {
        type: Boolean,
        default: false,
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const variants = {
    primary: 'bg-primary-600 text-white hover:bg-primary-700 active:bg-primary-800',
    secondary: 'bg-sand text-ink hover:bg-secondary-200',
    inverted: 'bg-ink text-white hover:bg-gray-800',
    outlined: 'border border-gray-300 bg-white text-ink hover:border-primary-600 hover:text-primary-700',
    accent: 'bg-secondary-600 text-ink hover:bg-secondary-500',
    tertiary: 'bg-tertiary-600 text-white hover:bg-tertiary-700',
    danger: 'bg-red-700 text-white hover:bg-red-800',
    ghost: 'text-primary-700 hover:bg-primary-50',
};

const sizes = {
    sm: 'min-h-9 gap-1.5 px-3 text-sm',
    md: 'min-h-11 gap-2 px-4 text-sm',
    lg: 'min-h-12 gap-2 px-5 text-base',
};

const tag = computed(() => {
    if (!props.href) {
        return 'button';
    }

    return props.native ? 'a' : Link;
});

const classes = computed(() => [
    'inline-flex items-center justify-center rounded-lg font-semibold transition-colors duration-150 disabled:cursor-not-allowed disabled:opacity-50',
    variants[props.variant],
    sizes[props.size],
    props.block ? 'w-full' : '',
]);
</script>

<template>
    <component
        :is="tag"
        :href="href"
        :type="href ? undefined : type"
        :disabled="href ? undefined : loading || undefined"
        :aria-busy="loading || undefined"
        :class="classes"
    >
        <Icon v-if="icon" :name="icon" size="sm" />
        <slot />
    </component>
</template>
